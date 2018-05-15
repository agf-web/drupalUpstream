<?php

namespace Drupal\agfirst_base_theme_support\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'HeaderSearchForm' block.
 *
 * @Block(
 *  id = "header_search_form",
 *  admin_label = @Translation("Search Form Block"),
 * )
 */
class HeaderSearchForm extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $build = [];

    $build['header_search_form']['#theme'] = 'agfirst_base_theme_header_search_block';
    return $build;
  }

}
