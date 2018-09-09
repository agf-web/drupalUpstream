<?php

namespace Drupal\webform_access;

use Drupal\Core\Entity\EntityDeleteForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\webform\Form\WebformDialogFormTrait;

/**
 * Provides a delete webform access group form.
 */
class WebformAccessGroupDeleteForm extends EntityDeleteForm {

  use WebformDialogFormTrait;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    return $this->buildDialogConfirmForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function getRedirectUrl() {
    return Url::fromRoute('entity.webform_access_group.collection');
  }

}
