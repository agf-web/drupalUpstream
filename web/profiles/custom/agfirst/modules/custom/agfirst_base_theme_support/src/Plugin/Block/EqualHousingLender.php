<?php

/**
 * @file
 * Contains \Drupal\agfirst_base_theme_support\Plugin\Block\EqualHousingLender.
 */

namespace Drupal\agfirst_base_theme_support\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;

/**
 * Provides a 'EqualHousingLender' block.
 *
 * @Block(
 *  id = "equal_housing_lender",
 *  admin_label = @Translation("Equal Housing Lender"),
 * )
 */
class EqualHousingLender extends BlockBase {


  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $block = [
      '#theme' => 'agfirst_base_theme_equal_housing_lender_block',
      '#cache' => [
        'max-age' => Cache::PERMANENT,
      ],
    ];

    $build['agfirst_base_theme_equal_housing_lender_block'] = $block;
    return $build;
  }

}
