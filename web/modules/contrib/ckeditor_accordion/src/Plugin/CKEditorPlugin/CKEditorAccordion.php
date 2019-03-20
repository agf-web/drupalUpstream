<?php

namespace Drupal\ckeditor_accordion\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\editor\Entity\Editor;

/**
 * Defines the "CKEditorAccordion" plugin.
 *
 * @CKEditorPlugin (
 *   id = "accordion",
 *   label = @Translation("CKEditorAccordion"),
 *   module = "ckeditor_accordion"
 * )
 */
class CKEditorAccordion extends CKEditorPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getConfig(Editor $editor) {
    $config = [];
    return $config;
  }

  /**
   * {@inheritdoc}
   */
  public function getDependencies(Editor $editor) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getFile() {
    return drupal_get_path('module', 'ckeditor_accordion') . '/js/plugins/accordion/plugin.js';
  }

  /**
   * {@inheritdoc}
   */
  public function getButtons() {
    $path = drupal_get_path('module', 'ckeditor_accordion') . '/js/plugins/accordion/icons';
    return [
      'Accordion' => [
        'label' => t('Add Accordion'),
        'image' => $path . '/accordion.png',
      ],
    ];
  }

}
