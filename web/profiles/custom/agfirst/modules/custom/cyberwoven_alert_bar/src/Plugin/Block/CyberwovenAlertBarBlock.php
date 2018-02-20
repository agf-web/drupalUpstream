<?php

namespace Drupal\cyberwoven_alert_bar\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a 'Cyberwoven Alert Bar' block.
 *
 * @Block(
 *   id = "cyberwoven_alert_bar",
 *   admin_label = @Translation("Alert Bar block"),
 * )
 */
class CyberwovenAlertBarBlock extends BlockBase implements BlockPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $config = \Drupal::config('cyberwoven_alert_bar.settings');

    // Build the block.
    $build = [];

    $block = [
      '#theme' => 'cyberwoven_alert_bar',
      '#attributes' => [
        'class' => ['cw-alert-bar', 'alert'],
        'id' => 'cw-alert-bar',
      ],
      '#enabled' => $config->get('enabled'),
      '#expires' => $config->get('expires'),
      '#severity' => $config->get('severity'),
      '#message' => array(
        '#markup' => $config->get('message')['value'],
      ),
      '#more_link_label' => $config->get('more_link_label'),
      '#more_link_url' => $config->get('more_link_url'),
      '#more_link_external' => $config->get('more_link_external'),
      '#cache' => [
        'tags' => ['config:cyberwoven_alert_bar.settings']
      ]
    ];

    if ($config->get('enabled')) {
      $build['cw-alert-bar'] = $block;
    }else{
      $build['cw-alert-bar'] = null;
    }

    return $build;

  }

  /**
   * {@inheritdoc}
   */
  public function blockAccess(AccountInterface $account) {

    $config = \Drupal::config('cyberwoven_alert_bar.settings');

    $enabled = $config->get('enabled') ?: 0;
    $today = new DrupalDateTime('today');
    $expires = new DrupalDateTime($config->get('expires')); // Defaults to "today".

    // If disabled, or expired, don't show the block.
    if (($enabled == 0) || (strtotime($today) > strtotime($expires))) {
      return AccessResult::forbidden();
    } else {
      return parent::blockAccess($account);
    }

  }

}
