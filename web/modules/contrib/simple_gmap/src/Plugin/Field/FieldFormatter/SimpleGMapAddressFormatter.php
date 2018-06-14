<?php

namespace Drupal\simple_gmap\Plugin\Field\FieldFormatter;

use Drupal\address\Plugin\Field\FieldFormatter\AddressDefaultFormatter;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'simple_gmap_address' formatter.
 *
 * @FieldFormatter(
 *   id = "simple_gmap_address",
 *   label = @Translation("Google Map from Address field"),
 *   field_types = {
 *     "address",
 *   }
 * )
 */
class SimpleGMapAddressFormatter extends AddressDefaultFormatter {

  use SimpleGMapTrait {
    viewElements as simpleGmapViewElements;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);
    $mapElements = $this->simpleGmapViewElements($items, $langcode);

    foreach ($items as $delta => $item) {
      $elements[$delta]['#post_render'][] = [static::class, 'mapPostRender'];
      $elements[$delta]['#map_elements'] = $mapElements[$delta];
      $elements[$delta]['#prefix'] = '';
      $elements[$delta]['#suffix'] = '';
    }

    return $elements;
  }

  /**
   * Post render callback.
   */
  public static function mapPostRender($content, $element) {
    $addressString = html_entity_decode(str_replace("\n", ',', strip_tags($content)), ENT_QUOTES);
    $map = $element['#map_elements'];
    $map['#address_text'] = $addressString;
    $map['#url_suffix'] = urlencode($addressString);
    return \Drupal::service('renderer')->renderRoot($map);
  }

}
