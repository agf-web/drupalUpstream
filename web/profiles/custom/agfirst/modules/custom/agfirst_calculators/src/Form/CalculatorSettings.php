<?php

/**
 * @file
 * Contains Drupal\agfirst_calculators\Form\CalculatorSettings.
 */

namespace Drupal\agfirst_calculators\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class FilemakerConfig.
 *
 * @package Drupal\agfirst_calculators\Form
 */
class CalculatorSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'agfirst_calculators.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'agfirst_calculators_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('agfirst_calculators.settings');

    $form['patronage_percent'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Patronage Percentage'),
      '#description' => $this->t('Used in Patronage Calculator. Use numbers or decimals only (example: 4 or 4.2). If left empty, the default is "19.24" '),
      '#default_value' => empty($config->get('patronage_percent')) ? '' : $config->get('patronage_percent'),
      '#required' => FALSE,
    ];

    return parent::buildForm($form, $form_state);
  } // buildForm()

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $config = $this->config('agfirst_calculators.settings');
    $config->set('patronage_percent', $form_state->getValue('patronage_percent'));
    $config->save();
  }
}
