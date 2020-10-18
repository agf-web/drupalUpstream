<?php

/**
 * @file
 * Contains \Drupal\agfirst_locations\Plugin\Block\LocationsDataBlock.
 */

namespace Drupal\agfirst_locations\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;

/**
 * Provides a 'LocationsDataBlock' block.
 *
 * @Block(
 *  id = "locations_data_block",
 *  admin_label = @Translation("Locations Data block"),
 * )
 */
class LocationsDataBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $block = [
      '#theme' => 'agfirst_locations_data_block',
      '#attributes' => [
        'class' => ['agfirst-locations-data'],
        'id' => 'agfirst-locations-data',
        'data' => $this->getData()
      ],
      '#cache' => [
        'max-age' => 0,
      ],
    ];

    $build['agfirst_locations_data_block'] = $block;
    return $build;
  }

  private function getData(){
    $data = array();
    $node = \Drupal::routeMatch()->getParameter('node');
    $getFarmCreditId = $node->field_get_farm_credit_id->value;
    if ($getFarmCreditId){
      $json = file_get_contents('https://www.getfarmcredit.com/locations_feed?key=1234&nid=' . $getFarmCreditId);
      $obj = json_decode($json);
      if ($obj){
        $object = $obj[0];
        $data['state'] = $object->State;
        $data['city'] = $object->City;
        $data['zip'] = $object->Zip;
        $data['address'] = $object->Address;
        $data['mailaddress'] = $object->shipping_address->Address;
        $data['phone1'] = $object->{'Phone 1'};
        $data['phone2'] = $object->{'Phone 2'};
        $data['fax'] = $object->Fax;
        $counties = array();
        foreach (explode(',', $object->County) as $countyItem){
          if ($countyItem!=""){
            $counties[]= $countyItem;
          }
        }
        foreach (explode(',', $object->CountyPartial) as $countyItem){
          if ($countyItem!=""){
            $counties[]= $countyItem;
          }
      	}
      	$data['counties']=$counties;
      }
    }
    return $data;
  }

}
