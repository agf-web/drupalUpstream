<?php

namespace Drupal\agfirst_embedded_forms\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'universal_form_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "universal_form_formatter",
 *   label = @Translation("Universal form formatter"),
 *   field_types = {
 *     "embedded_form_field"
 *   }
 * )
 */
class UniversalFormFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Implement default settings.
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      // Implement settings form.
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $config = \Drupal::config('agfirst_embedded_forms.configuration');

    foreach ($items as $delta => $item) {
      $type = $item->form_type;
      switch ($type) {
        case 'dynamics':
          $dynamics_link = $config->get('clickdynamics_location');
          $form_id = $this->viewValue($item);
          $elements[$delta] = [
            '#theme' => 'agfirst_embedded_forms_dynamics',
            '#form_vendor' => 'dynamics',
            '#attributes' => [
              'class' => ['dynamics-form-wrapper'],
              'id' => Html::cleanCssIdentifier('dynamics-form-field--' . $form_id),
            ],
            '#form_id' => $form_id,
            '#clickdimensions_link' => $dynamics_link,
          ];
          break;

        case 'jot':
          $form_id = $this->viewValue($item);
          $elements[$delta] = [
            '#theme' => 'agfirst_embedded_forms_jot',
            '#form_vendor' => 'jot',
            '#attributes' => [
              'class' => ['jot-form-wrapper'],
              'id' => Html::cleanCssIdentifier('jot-form-field--' . $form_id),
            ],
            '#jot_id' => $form_id,
          ];
          break;

        case 'shortstack':
          $form_id = $this->viewValue($item);
          $shortstack_loocation = $config->get('shortstack_location');

          $elements[$delta] = [
            '#theme' => 'agfirst_embedded_forms_shortstack',
            '#form_vendor' => 'shortstack',
            '#attributes' => [
              'class' => ['shortstack-form-wrapper'],
              'id' => Html::cleanCssIdentifier('shortstack-form-field--' . $form_id),
            ],
            '#frame_id' => $form_id,
            '#shortstack_location' => $shortstack_loocation,
          ];
          break;
      }

    }

    return $elements;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return string
   *   The textual output generated.
   */
  protected function viewValue(FieldItemInterface $item) {
    // The text value has no text format assigned to it, so the user input
    // should equal the output, including newlines.
    return Html::escape($item->form_code);
  }

}
