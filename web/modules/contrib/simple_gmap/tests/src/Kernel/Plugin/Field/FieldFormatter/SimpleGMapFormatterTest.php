<?php

namespace Drupal\Tests\simple_gmap\Kernel\Plugin\Field\FieldFormatter;

/**
 * Tests the field formatter 'simple_gmap'.
 *
 * @group simple_gmap
 */
class SimpleGMapFormatterTest extends FormatterTestBase {

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->createField('string', 'simple_gmap');
  }

}
