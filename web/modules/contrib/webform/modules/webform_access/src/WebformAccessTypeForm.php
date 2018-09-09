<?php

namespace Drupal\webform_access;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a form to define a webform access type.
 */
class WebformAccessTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\webform_access\WebformAccessTypeInterface $webform_access_type */
    $webform_access_type = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#required' => TRUE,
      '#attributes' => ($webform_access_type->isNew()) ? ['autofocus' => 'autofocus'] : [],
      '#default_value' => $webform_access_type->label(),
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#machine_name' => [
        'exists' => '\Drupal\webform_access\Entity\WebformAccessType::load',
        'label' => '<br/>' . $this->t('Machine name'),
      ],
      '#maxlength' => 32,
      '#field_suffix' => ' (' . $this->t('Maximum @max characters', ['@max' => 32]) . ')',
      '#required' => TRUE,
      '#disabled' => !$webform_access_type->isNew(),
      '#default_value' => $webform_access_type->id(),
    ];

    return parent::form($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\webform_access\WebformAccessTypeInterface $webform_access_type */
    $webform_access_type = $this->getEntity();
    $webform_access_type->save();

    $context = [
      '@label' => $webform_access_type->label(),
      'link' => $webform_access_type->toLink($this->t('Edit'), 'edit-form')->toString(),
    ];
    $this->logger('webform')->notice('Access type @label saved.', $context);

    $this->messenger()->addStatus($this->t('Access type %label saved.', ['%label' => $webform_access_type->label()]));

    $form_state->setRedirect('entity.webform_access_type.collection');
  }

}
