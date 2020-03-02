<?php

namespace Drupal\agfirst_content_log\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Defines a confirmation form to confirm deletion of content log data.
 */
class AgFirstContentLogConfirmDeleteForm extends ConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    \Drupal::messenger()->addStatus(t('The Content Log has been cleared.'));

    \Drupal::database()->truncate('agfirst_content_log')->execute();

    $form_state->setRedirect('agfirst_content_log.settings');

  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() : string {
    return "agfirst_content_log_confirm_delete_form";
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('agfirst_content_log.settings');
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Delete all Content Log data?');
  }

}
