<?php
/**
 * @file
 * Contains \Drupal\agfirst_branch_locator_details\Services\AgfirstBranchLocatorDetailsService.
 */
namespace Drupal\agfirst_branch_locator_details\Services;

class AgfirstBranchLocatorDetailsService {
  public function getDetails($getFarmCreditId) {
    if (!$getFarmCreditId) { return []; }

    // check if the cache already contains data
    $cid = 'gcfbranch:' . $getFarmCreditId;
    if ($branch_json = \Drupal::cache()->get($cid)) {
      return $branch_json->data;
    }

    // use config from main module `agfirst_branch_locator`
    $config = \Drupal::config('agfirst_branch_locator.settings');
    $authKey = $config->get('auth_key');

    // just in case
    if (!$authKey) {
      $authKey = 1234;
    }

    // data to be cached
    $data = array();
    $json = file_get_contents('https://www.getfarmcredit.com/locations_feed?key=' . $authKey . '&nid=' . $getFarmCreditId);
    $obj = json_decode($json);

    if ($obj) {
      $object = $obj[0];

      $data['branch'] = $object->Branch;
      $data['state'] = $object->State;
      $data['city'] = $object->City;
      $data['zip'] = $object->Zip;
      $data['address'] = $object->Address;
      $data['mailaddress'] = $object->shipping_address->Address;
      $data['mailzip'] = $object->shipping_address->Zip;
      $data['phone1'] = $object->{'Phone 1'};
      $data['phone2'] = $object->{'Phone 2'};
      $data['fax'] = $object->Fax;
      $counties = array();

      foreach (explode(',', $object->County) as $countyItem) {
        if ($countyItem != '') {
          $counties[] = $countyItem;
        }
      }

      foreach (explode(',', $object->CountyPartial) as $countyItem) {
        if ($countyItem != '') {
          $counties[] = $countyItem;
        }
      }

      $data['counties'] = $counties;
    }

    // set cache for `gfcbranch: . $nid` with cache time of 24hrs...
    $branch_json = $data;
    \Drupal::cache()->set($cid, $branch_json, time() + 86400);

    return $branch_json;
  }
}
