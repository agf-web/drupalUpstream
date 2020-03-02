<?php

namespace Drupal\agfirst_content_log\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements a global content modification log on AgFirst sites.
 *
 * @Block(
 *   id = "agfirst_content_log",
 *   admin_label = @Translation("Content Log"),
 * )
 */
class AgFirstContentLogBlock extends BlockBase implements BlockPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $form['agfirst_content_log_field'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Content Log Configuration Field'),
      '#size' => 60,
      '#description' => $this->t('A field to store a configuration value.'),
      '#default_value' => (empty($config['agfirst_content_log_field']) ? '' : $config['agfirst_content_log_field']),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $config = $this->getConfiguration();

    $config_value = (empty($config['agfirst_content_log_field']) ? '' : $config['agfirst_content_log_field']);


    // Build the block.
    $build = [];

    $block = [
      '#theme' => 'agfirst_content_log',
      '#attributes' => [
        'class' => ['agfirst-content-log'],
        'id' => $this->getBaseId(),
      ],
      '#content' => [
        'cid' => $config_value,
      ],
      '#cache' => [
        'tags' => ['config:agfirst_content_log.settings']
      ],
      '#attached' => [
        'library' => 'agfirst_content_log/agfirst_content_log.functions',
        'drupalSettings' => [
          'json_url' => $config_value,
        ],
      ]
    ];

    $build['agfirst-content-log-block'] = $block;

    return $build;

  }


  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();
    $this->configuration['agfirst_content_log_field'] = $values['agfirst_content_log_field'];
  }

}
