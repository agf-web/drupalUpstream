<?php

/**
 * @file
 * Contains Drupal\agfirst_careers\Form\OptitSettings.
 */

namespace Drupal\agfirst_careers\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class RssForm.
 *
 * @package Drupal\agfirst_careers\Form
 */
class RssForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'agfirst_careers.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'agfirst_careers_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('agfirst_careers.settings');

    $form['rss_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL'),
      '#description' => $this->t('RSS Url'),
      '#default_value' => empty($config->get('rss_url')) ? '' : $config->get('rss_url'),
      '#required' => TRUE,
    ];

    $form['no_jobs_message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Custom "No Jobs" message'),
      '#description' => $this->t('The message to be displayed if there are currently no job openings.'),
      '#default_value' => empty($config->get('no_jobs_message')) ? 'We have no current job openings, please check back later!': $config->get('no_jobs_message'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  } // buildForm()

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('agfirst_careers.settings')
      ->set('rss_url', $form_state->getValue('rss_url'))
      ->set('no_jobs_message', $form_state->getValue('no_jobs_message'))
      ->save();
  }

}
