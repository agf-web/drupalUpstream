<?php

namespace Drupal\agfirst_embedded_forms\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'universal_form_widget' widget.
 *
 * @FieldWidget(
 *   id = "universal_form_widget",
 *   label = @Translation("Universal form widget"),
 *   field_types = {
 *     "embedded_form_field"
 *   }
 * )
 */
class UniversalFormWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'size' => 30,
      'placeholder' => 'Paste the form ID from the provider embed code',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);

    $elements['placeholder'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Placeholder for Form ID'),
      '#default_value' => $this->getSetting('placeholder'),
      '#description' => $this->t('Text that will be shown inside the field until a value is entered.'),
    ];
    $elements['size'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Field size'),
      '#default_value' => $this->getSetting('size'),
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $placeholder = $this->getSetting('placeholder');

    if (empty($placeholder)) {
      $summary[] = $this->t('No placeholder');
    }
    else {
      $summary[] = $this->t('Placeholder: @placeholder', ['@placeholder' => $placeholder]);
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['form_code'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Form ID'),
      '#placeholder' => $this->getSetting('placeholder'),
      '#default_value' => isset($items[$delta]->form_code) ? $items[$delta]->form_code : NULL,
      '#maxlength' => 31,
      '#required' => $element['#required'],
    ];


    $element['form_type'] = [
      '#type' => 'select',
      '#default_value' => isset($items[$delta]->form_type) ? $items[$delta]->form_type : NULL,
      '#options' => [
        'jot' => 'Jot',
        'dynamics' => 'ClickDynamics',
        'shortstack' => 'Shortstack',
      ],
    ];

    return $element;
  }

}
