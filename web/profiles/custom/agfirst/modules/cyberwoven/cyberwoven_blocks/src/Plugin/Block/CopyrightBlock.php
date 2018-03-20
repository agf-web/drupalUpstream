<?php

namespace Drupal\cyberwoven_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'CopyrightBlock' block.
 *
 * @Block(
 *  id = "copyright_block",
 *  admin_label = @Translation("Copyright block"),
 * )
 */
class CopyrightBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'copyright_holder' => $this->t('Cyberwoven'),
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['copyright_holder'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Copyright holder'),
      '#description' => $this->t('The name of the of organization holding default copyrights for content on this site.'),
      '#default_value' => $this->configuration['copyright_holder'],
      '#maxlength' => 128,
      '#size' => 64,
      '#weight' => '0',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['copyright_holder'] = $form_state->getValue('copyright_holder');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $year = date('Y');
    $block = [
      '#theme' => 'cyberwoven_blocks_copyright',
      '#attributes' => [
        'class' => ['copyright'],
        'id' => 'copyright-block',
      ],
      '#copyright_holder' => $this->configuration['copyright_holder'],
      '#year'  => $year,
      '#cache' => [
        'max-age' => strtotime('01/01/' . (date('Y') + 1)) - time(),
      ],
    ];

    $build['copyright_block'] = $block;

    return $build;
  }

}
