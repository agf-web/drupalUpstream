<?php

namespace Drupal\simple_gmap\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'simple_gmap' formatter.
 *
 * @FieldFormatter(
 *   id = "simple_gmap",
 *   label = @Translation("Google Map from one-line address"),
 *   field_types = {
 *     "string",
 *     "computed",
 *     "computed_string",
 *   }
 * )
 */
class SimpleGMapFormatter extends FormatterBase {

  use SimpleGMapTrait;

}
