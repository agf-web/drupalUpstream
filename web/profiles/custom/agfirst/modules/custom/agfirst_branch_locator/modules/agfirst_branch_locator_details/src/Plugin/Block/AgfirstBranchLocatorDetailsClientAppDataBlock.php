<?php

/**
 * @file
 * Contains \Drupal\agfirst_branch_locator_details\Plugin\Block\AgfirstBranchLocatorDetailsClientAppDataBlock.
 */

namespace Drupal\agfirst_branch_locator_details\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;

/**
 * Provides a 'AgfirstBranchLocatorDetailsClientAppDataBlock' block.
 *
 * @Block(
 *  id = "agfirst_branch_locator_details_client_app_data_block",
 *  admin_label = @Translation("AgFirst Branch Locator Details - Client App Data block"),
 * )
 */
class AgfirstBranchLocatorDetailsClientAppDataBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $block = [
      '#type' => 'html_tag',
      '#tag' => 'script',
      '#value' => 'window.location_lookup = ' . $this->getData() . ';',
      '#attributes' => [
        'class' => ['agfirst-branch-locator-details-client-app-data-block'],
        'id' => 'agfirst-branch-locator-details-client-app-data-block'
      ],
      '#cache' => [
        'max-age' => 86400, // 24hrs...
        'tags' => [
          'node_type:branch'
        ]
      ],
    ];

    $build['agfirst_branch_locator_details_client_app_data_block'] = $block;
    return $build;
  }

  private function getData() {
    $array= array();
    $query = \Drupal::entityQuery('node')->condition('type','branch');
    $nids = $query->execute();
    $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);

    foreach ($nodes as $node){
      if (!empty($node->field_get_farm_credit_id)) {       
        if($node->field_get_farm_credit_id->value) {
          $innerArray=array();
          $innerArray['nid'] = $node->field_get_farm_credit_id->value;
          $innerArray['path'] = $node->toUrl()->toString();
          $array[] = $innerArray;
        } 
      }
    }
    return json_encode($array);
  }
}
