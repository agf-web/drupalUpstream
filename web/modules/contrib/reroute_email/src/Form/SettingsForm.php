<?php

namespace Drupal\reroute_email\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\RendererInterface;
use Egulias\EmailValidator\EmailValidator;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Implements a settings form for Reroute Email configuration.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * An editable config.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $rerouteConfig;

  /**
   * The module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * The email validator.
   *
   * @var \Egulias\EmailValidator\EmailValidator
   */
  protected $emailValidator;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'reroute_email_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['reroute_email.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('module_handler'),
      $container->get('renderer'),
      $container->get('email.validator')
    );
  }

  /**
   * Constructs a new object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler service.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer.
   * @param \Egulias\EmailValidator\EmailValidator $email_validator
   *   The email validator.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ModuleHandlerInterface $module_handler, RendererInterface $renderer, EmailValidator $email_validator) {
    parent::__construct($config_factory);
    $this->rerouteConfig = $this->config('reroute_email.settings');
    $this->moduleHandler = $module_handler;
    $this->renderer = $renderer;
    $this->emailValidator = $email_validator;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form[REROUTE_EMAIL_ENABLE] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable rerouting'),
      '#default_value' => $this->rerouteConfig->get(REROUTE_EMAIL_ENABLE),
      '#description' => $this->t('Check this box if you want to enable email rerouting. Uncheck to disable rerouting.'),
    ];

    $default_address = $this->rerouteConfig->get(REROUTE_EMAIL_ADDRESS);
    if (NULL === $default_address) {
      $default_address = $this->config('system.site')->get('mail');
    }

    $states = [
      'visible' => [':input[name=' . REROUTE_EMAIL_ENABLE . ']' => ['checked' => TRUE]],
    ];

    $form[REROUTE_EMAIL_ADDRESS] = [
      '#type' => 'textfield',
      '#title' => $this->t('Rerouting email addresses'),
      '#default_value' => $default_address,
      '#description' => $this->t('Provide a space, comma, or semicolon-delimited list of email addresses.<br/>Every destination email address which is not on "Whitelisted email addresses" list will be rerouted to these addresses.<br/>If the field is empty and no value is provided, all outgoing emails would be aborted and the email would be recorded in the recent log entries (if enabled).'),
      '#element_validate' => [[$this, 'validateFormEmails']],
      '#states' => $states,
    ];

    $form[REROUTE_EMAIL_WHITELIST] = [
      '#type' => 'textfield',
      '#title' => $this->t('Whitelisted email addresses'),
      '#default_value' => $this->rerouteConfig->get(REROUTE_EMAIL_WHITELIST),
      '#description' => $this->t('Provide a space, comma, or semicolon-delimited list of email addresses to pass through. <br/>Every destination email address which is not on this list will be rerouted.<br/>If the field is empty and no value is provided, all outgoing emails would be rerouted.<br/>We can use wildcard email "*@example.com" to whitelist all emails by the domain.'),
      '#element_validate' => [[$this, 'validateFormEmails']],
      '#states' => $states,
    ];

    $form[REROUTE_EMAIL_DESCRIPTION] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show rerouting description in mail body'),
      '#default_value' => $this->rerouteConfig->get(REROUTE_EMAIL_DESCRIPTION),
      '#description' => $this->t('Check this box if you want a message to be inserted into the email body when the mail is being rerouted. Otherwise, SMTP headers will be used to describe the rerouting. If sending rich-text email, leave this unchecked so that the body of the email will not be disturbed.'),
      '#states' => $states,
    ];

    $form[REROUTE_EMAIL_MESSAGE] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Display a Drupal status message after rerouting'),
      '#default_value' => $this->rerouteConfig->get(REROUTE_EMAIL_MESSAGE),
      '#description' => $this->t('Check this box if you would like a Drupal status message to be displayed to users after submitting an email to let them know it was aborted to send or rerouted to a different email address.'),
      '#states' => $states,
    ];

    // Format a list of modules that implement hook_mail.
    $mail_modules = $this->moduleHandler->getImplementations('mail');
    $all_modules = $this->moduleHandler->getModuleList();
    foreach ($mail_modules as $key => $module) {
      $mail_modules[$key] = $this->t("%module's module possible mail keys are `@machine_name`, `@machine_name_%`;", [
        '%module' => isset($all_modules[$module]->info['name']) ? $all_modules[$module]->info['name'] : $module,
        '@machine_name' => $module,
      ]);
    }
    $mail_modules = ['#theme' => 'item_list', '#items' => $mail_modules];

    $form['mailkeys'] = [
      '#type' => 'details',
      '#title' => $this->t('Advanced settings'),
      '#states' => $states,
      '#open' => $this->rerouteConfig->get(REROUTE_EMAIL_MAILKEYS, '') !== '',
    ];

    $form['mailkeys'][REROUTE_EMAIL_MAILKEYS] = [
      '#title' => $this->t('Filter by mail keys:'),
      '#type' => 'textarea',
      '#rows' => 3,
      '#default_value' => $this->rerouteConfig->get(REROUTE_EMAIL_MAILKEYS, ''),
      '#description' => $this->t('Provide a space, comma, semicolon, or newline-delimited list of message keys to be rerouted. Either module machine name or specific mail key can be used for that.<br/>Only matching messages will be rerouted. If left empty (as default), <strong>all emails will be selected for rerouting</strong>. Here is a list of modules that send emails:<br/>@modules_list Where `%` is one of a specific mail key provided by the module.', [
        '@modules_list' => $this->renderer->render($mail_modules),
      ]),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Validate multiple email addresses field.
   *
   * @param array $element
   *   A field array to validate.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function validateFormEmails(array $element, FormStateInterface $form_state) {
    // Allow only valid email addresses.
    $addresses = reroute_email_split_string($form_state->getValue($element['#name']));
    foreach ($addresses as $address) {
      if (!$this->emailValidator->isValid($address)) {
        $form_state->setErrorByName($element['#name'], $this->t('@address is not a valid email address.', ['@address' => $address]));
      }
    }

    // Save value in usable way to use as `to` param in drupal_mail.
    // String "email@example.com; ;; , ,," save just as "email@example.com".
    // This will be ignored if any validation errors occur.
    $form_state->setValue($element['#name'], implode(',', $addresses));
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->rerouteConfig
      ->set(REROUTE_EMAIL_ENABLE, $form_state->getValue(REROUTE_EMAIL_ENABLE))
      ->set(REROUTE_EMAIL_ADDRESS, $form_state->getValue(REROUTE_EMAIL_ADDRESS))
      ->set(REROUTE_EMAIL_WHITELIST, $form_state->getValue(REROUTE_EMAIL_WHITELIST))
      ->set(REROUTE_EMAIL_DESCRIPTION, $form_state->getValue(REROUTE_EMAIL_DESCRIPTION))
      ->set(REROUTE_EMAIL_MESSAGE, $form_state->getValue(REROUTE_EMAIL_MESSAGE))
      ->set(REROUTE_EMAIL_MAILKEYS, $form_state->getValue(REROUTE_EMAIL_MAILKEYS))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
