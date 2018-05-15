<?php

namespace Drupal\agfirst_base_theme_support\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\NodeType;
use Drupal\node\NodeInterface;

/**
 * Provides a 'SectionHeader' block.
 *
 * @Block(
 *  id = "section_header",
 *  admin_label = @Translation("Section Header Block"),
 * )
 */
class SectionHeader extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $build = [];

    $build['section_header']['#type'] = 'markup';
    $build['section_header']['#markup'] = '';
    $build['section_header'] = [
      '#cache' => [
        'contexts' => [
          'route',
          'url',
        ],
      ],
    ];

    // Empty content to help ensure cache tags bubble up.
    $content = [
      '#type' => 'markup',
      '#markup' => '',
    ];

    $route = \Drupal::service('current_route_match');
    $node = $route->getParameter('node');

    if ($node instanceof NodeInterface) {

      $type = NodeType::load($node->getType());
      $section = $type->getThirdPartySetting('agfirst_base_theme_support', 'section_header');

      $build['section_header']['#cache']['tags'] = $type->getCacheTags();

      if (!empty($section)) {
        $content = [
          '#theme' => 'agfirst_base_theme_support_section_block',
          '#section_name' => $section,
        ];
      }
    }

    $build = array_merge($build['section_header'], $content);

    return $build;
  }

}
