<?php

namespace Drupal\agfirst_embedded_forms\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ConfigurationForm.
 */
class ConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'agfirst_embedded_forms.configuration',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'agfirst_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('agfirst_embedded_forms.configuration');
    $form['clickdynamics_location'] = [
      '#type' => 'url',
      '#title' => $this->t('ClickDynamics Location'),
      '#description' => $this->t('This is defined in your embed codes as a variable called &quot;loc&quot;.'),
      '#default_value' => $config->get('clickdynamics_location'),
    ];
    $form['shortstack_location'] = [
      '#type' => 'url',
      '#title' => $this->t('ShortStack Script Source'),
      '#description' => $this->t('This is defined in your embed codes as src URL for the script tag.'),
      '#default_value' => $config->get('shortstack_location'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // @todo: Add regex to check form inputs against expected shape.
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('agfirst_embedded_forms.configuration')
      ->set('clickdynamics_location', $form_state->getValue('clickdynamics_location'))
      ->set('shortstack_location', $form_state->getValue('shortstack_location'))
      ->save();
  }

}
