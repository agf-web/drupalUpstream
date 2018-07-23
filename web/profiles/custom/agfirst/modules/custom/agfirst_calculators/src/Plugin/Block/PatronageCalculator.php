<?php

namespace Drupal\agfirst_calculators\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'PatronageCalculator' block.
 *
 * @Block(
 *  id = "patronage_calculator",
 *  admin_label = @Translation("Patronage Calculator"),
 * )
 */
class PatronageCalculator extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $config = \Drupal::config('agfirst_calculators.settings');

    $build['patronage_calculator'] = [
      '#theme' => 'agfirst_calculators_patronage',
      '#attributes' => [
        'class' => ['agfirst-calculator-patronage'],
      ],
      '#attached' => [
        'library' => ['agfirst_calculators/patronage_calculator'],
      ],
      '#patronage_percent' => $config->get('patronage_percent')
    ];

    return $build;
  }

}
