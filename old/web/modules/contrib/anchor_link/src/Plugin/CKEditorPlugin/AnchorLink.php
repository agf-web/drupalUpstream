<?php

namespace Drupal\anchor_link\Plugin\CKEditorPlugin;

use Drupal\editor\Entity\Editor;
use Drupal\ckeditor\CKEditorPluginBase;

/**
 * Defines the "link" plugin.
 *
 * @CKEditorPlugin(
 *   id = "link",
 *   label = @Translation("CKEditor Web link"),
 *   module = "anchor_link"
 * )
 */
class AnchorLink extends CKEditorPluginBase {

  /**
  * Implements \Drupal\ckeditor\Plugin\CKEditorPluginInterface::getFile().
  */
  function getFile() {
    return drupal_get_path('module', 'anchor_link') . '/js/plugins/link/plugin.js';
  }
  
  /**
   * {@inheritdoc}
   */
  public function getDependencies(Editor $editor) {
    return [
      'fakeobjects',
    ];
  }
  /**
   * {@inheritdoc}
   */
  public function getLibraries(Editor $editor) {
    return [];
  }
  
    /**
   * {@inheritdoc}
   */
  public function isInternal() {
    return FALSE;
  }

  /**
   * Implements \Drupal\ckeditor\Plugin\CKEditorPluginButtonsInterface::getButtons().
   */
  function getButtons() {
    return [
      'Link' => [
        'label' => t('Link'),
        'image' => drupal_get_path('module', 'anchor_link') . '/js/plugins/link/icons/link.png',
      ],
      'Unlink' => [
        'label' => t('Unlink'),
        'image' => drupal_get_path('module', 'anchor_link') . '/js/plugins/link/icons/unlink.png',
      ],
      'Anchor' => [
        'label' => t('Anchor'),
        'image' => drupal_get_path('module', 'anchor_link') . '/js/plugins/link/icons/anchor.png',
      ]
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig(Editor $editor) {
    return [];
  }
}
