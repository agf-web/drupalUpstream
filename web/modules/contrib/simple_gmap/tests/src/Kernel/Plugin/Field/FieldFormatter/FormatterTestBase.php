<?php

namespace Drupal\Tests\simple_gmap\Kernel\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\entity_test\Entity\EntityTest;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\simple_gmap\Plugin\Field\FieldFormatter\SimpleGMapFormatter;

/**
 * Base class for formatter tests.
 */
abstract class FormatterTestBase extends EntityKernelTestBase {

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

    $this->fieldName = mb_strtolower($this->randomMachineName());
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
   * Returns the default expected query.
   *
   * @return array
   *   The expected generated query.
   */
  protected function getDefaultQueryOutput() {
    return 'q=Place+de+l%27Universit%C3%A9-du-Qu%C3%A9bec%2C+boulevard+Charest+Est%2C+Qu%C3%A9bec%2C+QC+G1K';
  }

  /**
   * Returns the default address HTML output.
   *
   * @return string
   *   The HTML output for the field.
   */
  protected function getDefaultAddressOutput() {
    return 'Place de l&#039;Université-du-Québec, boulevard Charest Est, Québec, QC G1K';
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
    return $this->container->get('renderer')->renderRoot($content);
  }

  /**
   * Tests outputting a gmap with various settings.
   *
   * @param array $raws
   *   An array whose keys are the texts to look for and the values a boolean
   *   that indicates whether or not they are expected or not.
   * @param array $settings
   *   The formatter settings.
   * @param mixed $address
   *   (optional) The address to test with. If not provided, the default address
   *   will be used.
   *
   * @dataProvider settingsProvider
   */
  public function testSettings(array $raws, array $settings, $address = NULL) {
    if (empty($address)) {
      $address = $this->getDefaultAddress();
    }

    $component = $this->display->getComponent($this->fieldName);
    $component['settings'] = NestedArray::mergeDeepArray([SimpleGMapFormatter::defaultSettings(), $settings], TRUE);
    $this->display->setComponent($this->fieldName, $component);

    $entity = EntityTest::create([]);
    $entity->{$this->fieldName} = $address;
    $entity->save();

    // Render content and strip newlines from it.
    $rendered_content = (string) $this->renderEntityFields($entity, $this->display);
    $rendered_content = str_replace(["\n", "\r"], '', $rendered_content);

    foreach ($raws as $raw => $is_expected) {
      if ($is_expected) {
        $this->assertContains($raw, $rendered_content);
      }
      else {
        $this->assertNotContains($raw, $rendered_content);
      }
    }
  }

  /**
   * Data provider for ::testSettings().
   */
  public function settingsProvider() {
    return [
      'include_text_enabled' => [
        [
          $this->getDefaultQueryOutput() => TRUE,
          '<p class="simple-gmap-address">' . $this->getDefaultAddressOutput() . '</p>' => TRUE,
        ],
        [
          'include_text' => TRUE,
        ],
      ],
      'include_text_disabled' => [
        [
          $this->getDefaultQueryOutput() => TRUE,
          '<p class="simple-gmap-address">' . $this->getDefaultAddressOutput() . '</p>' => FALSE,
        ],
        [
          'include_text' => FALSE,
        ],
      ],
    ];
  }

}
