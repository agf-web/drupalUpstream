<?php

namespace Drupal\agfirst_embedded_forms\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'embedded_form_field' field type.
 *
 * @FieldType(
 *   id = "embedded_form_field",
 *   label = @Translation("Embedded form field"),
 *   description = @Translation("Provides support for several embedded form types."),
 *   default_widget = "universal_form_widget",
 *   default_formatter = "universal_form_formatter"
 * )
 */
class EmbeddedFormField extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {

    return [] + parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    // Prevent early t() calls by using the TranslatableMarkup.
    $properties['form_code'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Form ID Code'))
      ->setSetting('case_sensitive', FALSE)
      ->setRequired(TRUE);

    $properties['form_type'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Form Provider'))
      ->setSetting('case_sensitive', FALSE)
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'columns' => [
        'form_code' => [
          'type' => 'varchar',
          'length' => 31,
          'binary' => FALSE,
        ],
        'form_type' => [
          'type' => 'varchar',
          'length' => 31,
          'binary' => FALSE,
        ],
      ],
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
//  public function getConstraints() {
//    $constraints = parent::getConstraints();
//
//    return $constraints;
//  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition) {

    // Note: these are fake, if they render forms that's by chance not design.
    $samples = [
      'Jot' => '12345678901234',
      'ClickDynamics' => 'jssn8bpleefdassqvqmgoa',
      'Shortstack' => 'DQT015',
    ];

    $select = rand(0, count(samples) - 1);
    $key = array_keys($samples)[$select];

    return [$key => $samples[$key]];
  }

  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    $elements = [];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('form_code')->getValue();
    return empty($value);
  }

}
