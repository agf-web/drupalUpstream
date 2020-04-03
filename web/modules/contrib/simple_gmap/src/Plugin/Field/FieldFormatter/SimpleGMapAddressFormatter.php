<?php

namespace Drupal\simple_gmap\Plugin\Field\FieldFormatter;

use Drupal\address\AddressInterface;
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
    $map_elements = $this->simpleGmapViewElements($items, $langcode);

    foreach ($items as $delta => $item) {
      // Render address, but without prefix and suffix as that contains
      // additional html we don't want.
      $elements[$delta]['#prefix'] = '';
      $elements[$delta]['#suffix'] = '';
      $address_html = $this->getRenderer()->renderRoot($elements[$delta]);

      $elements[$delta] = [
        '#address_text' => [
          '#type' => 'markup',
          '#markup' => html_entity_decode($address_html),
        ],
        '#url_suffix' => $this->getAddressAsQueryString(clone $item, $langcode),
      ] + $map_elements[$delta];
    }

    return $elements;
  }

  /**
   * Returns the render service.
   *
   * @return \Drupal\Core\Render\RendererInterface
   *   The render service.
   */
  protected function getRenderer() {
    return \Drupal::service('renderer');
  }

  /**
   * Returns the fields that should be excluded from the query send to Google.
   *
   * @return array
   *   A list of field names.
   */
  protected function getExcludedAddressFieldForMap() {
    return [
      'organization',
      'given_name',
      'additional_name',
      'family_name',
    ];
  }

  /**
   * Generates an url encoded address string.
   *
   * @param \Drupal\address\AddressInterface $address
   *   The address.
   * @param string $langcode
   *   The language that should be used to render the field.
   */
  protected function getAddressAsQueryString(AddressInterface $address, $langcode) {
    // First, unset certain fields not used for the query.
    foreach ($this->getExcludedAddressFieldForMap() as $field_name) {
      $address->set($field_name, NULL);
    }

    // Generate address render element.
    $address_element = $this->viewElement($address, $langcode);

    // Render the address.
    $address_html = $this->getRenderer()->renderRoot($address_element);

    // Convert address to a comma delimited string.
    $address_string = html_entity_decode(str_replace("\n", ',', strip_tags($address_html)), ENT_QUOTES);

    // Remove double commas.
    $address_parts = explode(',', $address_string);
    $address_parts = array_filter($address_parts);
    $address_string = implode(',', $address_parts);

    // And url encode it.
    return urlencode($address_string);
  }

}
