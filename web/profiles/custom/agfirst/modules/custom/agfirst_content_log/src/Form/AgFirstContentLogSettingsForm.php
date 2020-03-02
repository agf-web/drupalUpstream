<?php

/**
 * @file
 * Contains \Drupal\agfirst_content_log\Form\AgFirstContentLogSettingsForm
 */
namespace Drupal\agfirst_content_log\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Url;

/**
 * Configure agfirst_content_log settings for this site.
 */
class AgFirstContentLogSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'agfirst_content_log_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'agfirst_content_log.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('agfirst_content_log.settings');

    $form['agfirst_clear_log'] = [
      '#type' => 'details',
      '#title' => t('Clear content log'),
      '#open' => TRUE,
    ];

    $form['agfirst_clear_log']['clear'] = [
      '#type' => 'submit',
      '#value' => t('Clear Log Data'),
      '#submit' => ['::submitClearLog'],
    ];

    $form['agfirst_clear_log']['clear'] = [
      '#type' => 'link',
      '#url' => Url::fromRoute('agfirst_content_log.delete'),
      '#title' => t('Clear Log Data'),
      '#attributes' => [
        'class' => ['button']
      ],
    ];

    $form['agfirst_log_settings'] = [
      '#type' => 'details',
      '#title' => t('Content Log Settings'),
      '#open' => TRUE,
    ];

    $form['agfirst_log_settings']['agfirst_content_log_rowcount'] = [
      '#type' => 'number',
      '#title' => $this->t('Rows per page'),
      '#placeholder' => $this->t('Link label'),
      '#size' => 4,
      '#min' => 1,
      '#max' => 100,
      '#description' => $this->t('Set the number of rows per page on the Content Log table.'),
      '#default_value' => (($config->get('acl_rowcount')) ?: 50),
      '#required' => TRUE,
    ];


    $form['agfirst_csv_settings'] = [
      '#type' => 'details',
      '#title' => t('CSV File Settings'),
      '#open' => TRUE,
    ];

    $form['agfirst_csv_settings']['agfirst_csv_filename'] = [
      '#type' => 'textfield',
      '#title' => $this->t('CSV Filename'),
      '#description' => $this->t('Set the name of the CSV created when "Export to CSV" is clicked.<br><b>Note:</b> Tokens can be used to create the filename.'),
      '#maxlenth' => 128,
      '#required' => TRUE,
      '#default_value' => (($config->get('acl_csv_filename')) ?: 'content-log.csv'),
    ];

    // Add the token tree UI.
    $form['agfirst_csv_settings']['token_tree'] = [
      '#theme' => 'token_tree_link',
      '#token_types' => ['user'],
      '#show_restricted' => TRUE,
      '#weight' => 90,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $config = \Drupal::service('config.factory')->getEditable('agfirst_content_log.settings');
    $config
      ->set('acl_rowcount', $form_state->getValue('agfirst_content_log_rowcount'))
      ->set('acl_csv_filename', $form_state->getValue('agfirst_csv_filename'))
      ->save();

    Cache::invalidateTags(array('config:agfirst_content_log.settings'));

  }
}
