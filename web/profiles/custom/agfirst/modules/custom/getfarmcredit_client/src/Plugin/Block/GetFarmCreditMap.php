<?php

namespace Drupal\getfarmcredit_client\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'GetFarmCreditMap' block.
 *
 * @Block(
 *  id = "getfarmcredit_map",
 *  admin_label = @Translation("GetFarmCredit Map"),
 * )
 */
class GetFarmCreditMap extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
          ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['coming_soon'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Coming Soon'),
    '#description' => $this->t('Settings will be added to future versions.'),
      '#default_value' => $this->configuration['coming_soon'],
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['coming_soon'] = $form_state->getValue('coming_soon');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $block = [
      '#theme' => 'getfarmcredit_client_map',
      '#attributes' => [],
    ];

    return $block;
  }

}
