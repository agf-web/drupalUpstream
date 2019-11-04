<?php

/**
 * @file
 * Contains \Drupal\agfirst_extlink_override\Plugin\Block\AgFirstLinkMessage.
 */

namespace Drupal\agfirst_extlink_override\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;

/**
 * Provides a 'AgFirstLinkMessage' block.
 *
 * @Block(
 *  id = "agfirst_link_message",
 *  admin_label = @Translation("Ag First link message"),
 * )
 */
class AgFirstLinkMessage extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = \Drupal::config('extlink.settings');
    $build = [];
    $block = [
      '#theme' => 'agfirst_extlink_override_link_message',
      '#attributes' => [
        'class' => ['agfirst-link-dialog'],
        'id' => 'agfirst-link-dialog-block',
      ],
      '#message' => $config->get('extlink_alert_text'),
      '#cache' => [
        'max-age' => Cache::PERMANENT, // TODO: link CacheTag to config message
      ],
    ];

    $build['agfirst_link_message'] = $block;
    return $build;
  }

}
