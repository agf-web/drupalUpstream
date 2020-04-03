<?php

namespace Drupal\Tests\simple_gmap\Kernel\Plugin\Field\FieldFormatter;

/**
 * Tests the field formatter 'simple_gmap_address'.
 *
 * @group simple_gmap
 */
class SimpleGMapAddressFormatterTest extends FormatterTestBase {

  /**
   * {@inheritdoc}
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

    $this->createField('address', 'simple_gmap_address');
  }

  /**
   * Data provider for ::testSettings().
   */
  public function settingsProvider() {
    return parent::settingsProvider() + [
      // Names should *not* be send with the query, but they should be displayed
      // underneath the map when 'include_text' option is enabled.
      'with_names' => [
        [
          $this->getDefaultQueryOutput() => TRUE,
          '<p class="simple-gmap-address"><span class="given-name">John</span> <span class="family-name">Doe</span><br><span class="organization">Drupa</span><br><span class="address-line1">Place de l&#039;Université-du-Québec</span><br><span class="address-line2">boulevard Charest Est</span><br><span class="locality">Québec</span> <span class="postal-code">QC G1K</span><br><span class="country">Canada</span></p>' => TRUE,
        ],
        [
          'include_text' => TRUE,
        ],
        [
          'country_code' => 'CA',
          'locality' => 'Québec',
          'postal_code' => 'QC G1K',
          'address_line1' => "Place de l'Université-du-Québec",
          'address_line2' => 'boulevard Charest Est',
          'organization' => 'Drupa',
          'given_name' => 'John',
          'family_name' => 'Doe',
        ],
      ],
    ];
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

  /**
   * {@inheritdoc}
   */
  protected function getDefaultQueryOutput() {
    return 'q=Canada%2CQu%C3%A9bec%2CQC+G1K%2CPlace+de+l%27Universit%C3%A9-du-Qu%C3%A9bec%2Cboulevard+Charest+Est';
  }

  /**
   * {@inheritdoc}
   */
  protected function getDefaultAddressOutput() {
    return '<span class="address-line1">Place de l&#039;Université-du-Québec</span><br><span class="address-line2">boulevard Charest Est</span><br><span class="locality">Québec</span> <span class="postal-code">QC G1K</span><br><span class="country">Canada</span>';
  }

}
