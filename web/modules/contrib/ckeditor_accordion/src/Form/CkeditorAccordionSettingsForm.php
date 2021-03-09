<?php

namespace Drupal\ckeditor_accordion\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CkeditorAccordionSettingsForm.
 *
 * @package Drupal\ckeditor_accordion\Form
 */
class CkeditorAccordionSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ckeditor_accordion_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return ['ckeditor_accordion.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('ckeditor_accordion.settings');

    $form['collapse_all'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Collapse all tabs by default'),
      '#return_value' => 1,
      '#default_value' => $config->get('collapse_all') ?: 0,
    ];

    $form['keep_rows_open'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Keep accordion rows open when opening another one'),
      '#return_value' => 1,
      '#default_value' => $config->get('keep_rows_open') ?: 0,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('ckeditor_accordion.settings');
    $values = $form_state->getValues();

    $config->set('collapse_all', $values['collapse_all']);
    $config->set('keep_rows_open', $values['keep_rows_open']);
    $config->save();

    parent::submitForm($form, $form_state);
  }

}
