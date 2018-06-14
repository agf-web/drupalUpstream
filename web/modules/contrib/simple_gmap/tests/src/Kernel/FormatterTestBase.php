<?php

namespace Drupal\Tests\simple_gmap\Kernel;

use Drupal\Component\Utility\Unicode;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\entity_test\Entity\EntityTest;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\KernelTests\KernelTestBase;
use Drupal\simple_gmap\Plugin\Field\FieldFormatter\SimpleGMapFormatter;

/**
 * Base class for formatter tests.
 */
abstract class FormatterTestBase extends KernelTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'system',
    'field',
    'text',
    'filter',
    'entity_test',
    'user',
    'simple_gmap',
  ];

  /**
   * The generated field name.
   *
   * @var string
   */
  protected $fieldName;

  /**
   * The entity display.
   *
   * @var \Drupal\Core\Entity\Display\EntityViewDisplayInterface
   */
  protected $display;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->installConfig(['field']);
    $this->installEntitySchema('entity_test');

    $this->fieldName = Unicode::strtolower($this->randomMachineName());
  }

  /**
   * Creates an entity_test field of the given type.
   *
   * @param string $field_type
   *   The field type.
   * @param string $formatter_id
   *   The formatter ID.
   */
  protected function createField($field_type, $formatter_id) {
    $field_storage = FieldStorageConfig::create([
      'field_name' => $this->fieldName,
      'entity_type' => 'entity_test',
      'type' => $field_type,
    ]);
    $field_storage->save();

    $field = FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => 'entity_test',
      'label' => $this->randomMachineName(),
    ]);
    $field->save();

    $this->display = entity_get_display('entity_test', 'entity_test', 'default');
    $this->display->setComponent($this->fieldName, [
      'type' => $formatter_id,
      'settings' => [],
    ]);
    $this->display->save();
  }

  /**
   * Returns the default address to use for the test.
   *
   * @return array
   *   The value for the field.
   */
  protected function getDefaultAddress() {
    return [
      'value' => "Place de l'Université-du-Québec, boulevard Charest Est, Québec, QC G1K",
    ];
  }

  /**
   * Returns the default address HTML output.
   *
   * @return string
   *   The HTML output for the field.
   */
  protected function getDefaultAddressOutput() {
    return "Place de l&#039;Université-du-Québec, boulevard Charest Est, Québec, QC G1K";
  }

  /**
   * Renders fields of a given entity with a given display.
   *
   * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
   *   The entity object with attached fields to render.
   * @param \Drupal\Core\Entity\Display\EntityViewDisplayInterface $display
   *   The display to render the fields in.
   *
   * @return string
   *   The rendered entity fields.
   */
  protected function renderEntityFields(FieldableEntityInterface $entity, EntityViewDisplayInterface $display) {
    $content = $display->build($entity);
    $content = $this->render($content);
    return $content;
  }

  /**
   * Tests outputting a gmap with various settings.
   *
   * @param string $raw
   *   The raw text to assert.
   * @param array $settings
   *   The formatter settings.
   * @param bool $is_expected
   *   (optional) Whether or not the raw text is expected.
   *   Defaults to true.
   * @param mixed $address
   *   (optional) The address to test with. If not provided, the default address
   *   will be used.
   *
   * @dataProvider settingsProvider
   */
  public function testSettings($raw, array $settings, $is_expected = TRUE, $address = NULL) {
    if (empty($address)) {
      $address = $this->getDefaultAddress();
    }

    $component = $this->display->getComponent($this->fieldName);
    $component['settings'] = $settings + SimpleGMapFormatter::defaultSettings();
    $this->display->setComponent($this->fieldName, $component);

    $entity = EntityTest::create([]);
    $entity->{$this->fieldName} = $address;
    $entity->save();

    $this->renderEntityFields($entity, $this->display);
    if ($is_expected) {
      $this->assertRaw($raw);
    }
    else {
      $this->assertNoRaw($raw);
    }
  }

  /**
   * Data provider for ::testSettings().
   */
  public function settingsProvider() {
    return [
      'include_text_enabled' => [
        '<p class="simple-gmap-address">' . $this->getDefaultAddressOutput() . '</p>',
        [
          'include_text' => TRUE,
        ],
      ],
      'include_text_disabled' => [
        '<p class="simple-gmap-address">' . $this->getDefaultAddressOutput() . '</p>',
        [
          'include_text' => FALSE,
        ],
        FALSE,
      ],
    ];
  }

}
