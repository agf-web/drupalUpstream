<?php

namespace Drupal\Tests\telephone_formatter\Functional;

use Drupal\field\Entity\FieldConfig;
use Drupal\Tests\BrowserTestBase;
use Drupal\field\Entity\FieldStorageConfig;
use libphonenumber\PhoneNumberFormat;

/**
 * Tests the creation of telephone fields.
 *
 * @group telephone
 */
class FieldFormatterTest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'field',
    'node',
    'telephone',
    'telephone_formatter',
  ];

  /**
   * A user with permission to create articles.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $webUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->drupalCreateContentType(['type' => 'page']);
    $this->webUser = $this->drupalCreateUser(['create page content', 'edit own page content']);
    $this->drupalLogin($this->webUser);
  }

  /**
   * Helper function for testTelephoneField().
   */
  public function testTelephoneFieldFallback() {
    $this->generateTelephoneField();
    $node = $this->drupalCreateNode(['field_telephone' => ['98765432']]);
    $this->drupalGet($node->toUrl());
    $this->assertSession()->responseContains('98765432');
  }

  /**
   * Helper method for telephone field generation.
   */
  protected function generateTelephoneField($settings = []) {
    // Add the telephone field to the article content type.
    FieldStorageConfig::create([
      'field_name' => 'field_telephone',
      'entity_type' => 'node',
      'type' => 'telephone',
    ])->save();
    FieldConfig::create([
      'field_name' => 'field_telephone',
      'label' => 'Telephone Number',
      'entity_type' => 'node',
      'bundle' => 'page',
    ])->save();

    entity_get_display('node', 'page', 'default')
      ->setComponent('field_telephone', [
        'type' => 'telephone_formatter',
        'weight' => 1,
        'settings' => $settings + [
          'format' => PhoneNumberFormat::INTERNATIONAL,
          'link' => TRUE,
          'default_country' => NULL,
        ],
      ])
      ->save();
  }

}
