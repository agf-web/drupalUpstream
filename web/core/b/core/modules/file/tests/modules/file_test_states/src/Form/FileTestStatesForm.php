<?php

namespace Drupal\file_test_states\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * A simple form with a file and manage_file to test states.
 */
class FileTestStatesForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'file_test_states_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['toggle'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Toggle fields'),
    ];
    $form['managed_file_initially_visible'] = [
      '#type' => 'managed_file',
      '#title' => t('Managed File Initially Visible'),
      '#states' => [
        'visible'  => [':input[name="toggle"]' => ['checked' => FALSE]],
      ],
    ];
    $form['managed_file_initially_hidden'] = [
      '#type' => 'managed_file',
      '#title' => t('Managed File Initially Hidden'),
      '#states' => [
        'visible'  => [':input[name="toggle"]' => ['checked' => TRUE]],
      ],
    ];
    $form['managed_file_initially_optional'] = [
      '#type' => 'managed_file',
      '#title' => t('Managed File Initially Optional'),
      '#states' => [
        'required'  => [':input[name="toggle"]' => ['checked' => TRUE]],
      ],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {}

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {}

}
