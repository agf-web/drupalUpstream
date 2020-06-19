<?php

/**
 * @file
 * Contains \Drupal\agfirst_branch_locator_details\Plugin\Block\AgfirstBranchLocatorDetailsMapBlock.
 */

namespace Drupal\agfirst_branch_locator_details\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\agfirst_branch_locator_details\Services\AgfirstBranchLocatorDetailsService;

/**
 * Provides a 'AgfirstBranchLocatorDetailsMapBlock' block.
 *
 * @Block(
 *  id = "agfirst_branch_locator_details_map_block",
 *  admin_label = @Translation("AgFirst Branch Locator Details - Map block"),
 * )
 */
class AgfirstBranchLocatorDetailsMapBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected $detail_service;

  /**
   * Construct.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    AgfirstBranchLocatorDetailsService $detail_service
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->detail_service = $detail_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('agfirst_branch_locator_details.detail_service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $detail_data = $this->getData();
    $build = [];
    $block = [
      '#theme' => 'agfirst_branch_locator_details_map_block',
      '#detail_data' => $detail_data,
      '#attributes' => [
        'class' => ['agfirst-branch-locator-details-map-block'],
        'id' => 'agfirst-branch-locator-details-map-block',
      ],
      '#cache' => [
        'max-age' => 0,
      ],
    ];

    $build['agfirst_branch_locator_details_map_block'] = $block;
    return $build;
  }

  private function getData(){
    $node = \Drupal::routeMatch()->getParameter('node');
    $getFarmCreditId = $node->field_get_farm_credit_id->value;

    return $this->detail_service->getDetails($getFarmCreditId);
  }

}
