<?php

/**
 * @file
 * Contains \Drupal\agfirst_locations\Plugin\Block\LocationsExtrasBlock.
 */

namespace Drupal\agfirst_locations\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;

/**
 * Provides a 'LocationsExtrasBlock' block.
 *
 * @Block(
 *  id = "locations_extras_block",
 *  admin_label = @Translation("Locations Extras block"),
 * )
 */
class LocationsExtrasBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $block = [
      '#theme' => 'agfirst_locations_extras_block',
      '#attributes' => [
        'class' => ['agfirst-locations-extras'],
        'id' => 'agfirst-locations-extras'
      ],
      '#cache' => [
        'max-age' => 0,
      ],
    ];
    $block['#jsondata'] = $this->getData();
    $build['agfirst_locations_extras_block'] = $block;
    return $build;
  }

  private function getData(){
      $array= array();
      $query = \Drupal::entityQuery('node')->condition('type','location');
      $nids = $query->execute();
      $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);

      foreach ($nodes as $node){
        if (!empty($node->field_get_farm_credit_id)){       
          if($node->field_get_farm_credit_id->value){
            $innerArray=array();
            $innerArray['nid']= $node->field_get_farm_credit_id->value;
            $innerArray['path']= $node->toUrl()->toString();
            $array[]=$innerArray;
          } 
        }
      }
      return json_encode($array);
    }

}