<?php

namespace Drupal\Tests\simple_gmap\Kernel;

/**
 * Tests the field formatter 'simple_gmap_address'.
 *
 * @group simple_gmap
 */
class SimpleGMapAddressFormatterTest extends FormatterTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'system',
    'field',
    'language',
    'text',
    'entity_test',
    'user',
    'simple_gmap',
    'address',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->installConfig(['system']);
    $this->installConfig(['text']);
    $this->installConfig(['address']);

    $this->createField('address', 'simple_gmap_address');
  }

  /**
   * {@inheritdoc}
   */
  protected function getDefaultAddress() {
    return [
      'country_code' => 'CA',
      'locality' => 'Québec',
      'postal_code' => 'QC G1K',
      'address_line1' => "Place de l'Université-du-Québec",
      'address_line2' => 'boulevard Charest Est',
    ];
  }

}
