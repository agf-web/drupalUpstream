<?php

namespace Drupal\agfirst_calculators\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'LoanCalculator' block.
 *
 * @Block(
 *  id = "loan_calculator",
 *  admin_label = @Translation("Loan Payment Calculator"),
 * )
 */
class LoanCalculator extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['loan_calculator'] = [
      '#theme' => 'agfirst_calculators_loan',
      '#attributes' => [
        'class' => ['agfirst-loan-calculator'],
      ],
      '#attached' => [
        'library' => ['agfirst_calculators/loan_calculator'],
      ],
    ];

    return $build;
  }

}
