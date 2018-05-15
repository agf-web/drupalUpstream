<?php

namespace Drupal\search_api_attachments\Plugin\Field\FieldFormatter;

use Drupal\Core\Config\Config;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\file\Plugin\Field\FieldFormatter\FileFormatterBase;
use Drupal\search_api\Processor\ProcessorPluginManager;
use Drupal\search_api_attachments\Plugin\search_api\processor\FilesExtrator;
use Drupal\search_api_attachments\TextExtractorPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * File formatter displaying text extracted form attachment document.
 *
 * @FieldFormatter(
 *   id = "file_extracted_text",
 *   label = @Translation("Text extracted from attachment"),
 *   field_types = {
 *     "file"
 *   }
 * )
 */
class ExtractedText extends FileFormatterBase implements ContainerFactoryPluginInterface {

  /**
   * Files extractor config.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * Module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Search API Processor Plugin Manager.
   *
   * @var \Drupal\search_api\Processor\ProcessorPluginManager
   */
  protected $processorPluginManager;

  /**
   * Search API Attachments Text Extractor Plugin Manager.
   *
   * @var \Drupal\search_api_attachments\TextExtractorPluginManager
   */
  protected $textExtractorPluginManager;

  /**
   * FilesExtractor processor plugin.
   *
   * @var \Drupal\search_api_attachments\Plugin\search_api\processor\FilesExtrator
   */
  protected $extractor;

  /**
   * Extraction plugin.
   *
   * @var \Drupal\search_api_attachments\TextExtractorPluginInterface
   */
  protected $extractionMethod;

  /**
   * ExtractedText constructor.
   *
   * @param string $pluginId
   * @param mixed $pluginDefinition
   * @param \Drupal\Core\Field\FieldDefinitionInterface $fieldDefinition
   * @param array $settings
   * @param string $label
   * @param string $viewMode
   * @param array $thirdPartySettings
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   * @param \Drupal\search_api\Processor\ProcessorPluginManager $processorPluginManager
   * @param \Drupal\search_api_attachments\TextExtractorPluginManager $textExtractorPluginManager
   * @param \Drupal\Core\Config\Config $config
   */
  public function __construct($pluginId, $pluginDefinition, FieldDefinitionInterface $fieldDefinition, array $settings, $label, $viewMode, array $thirdPartySettings, ModuleHandlerInterface $moduleHandler, ProcessorPluginManager $processorPluginManager, TextExtractorPluginManager $textExtractorPluginManager, Config $config) {
    parent::__construct($pluginId, $pluginDefinition, $fieldDefinition, $settings, $label, $viewMode, $thirdPartySettings);

    $this->moduleHandler = $moduleHandler;
    $this->processorPluginManager = $processorPluginManager;
    $this->textExtractorPluginManager = $textExtractorPluginManager;
    $this->config = $config;

    $extractorPluginId = $this
      ->config
      ->get('extraction_method');
    $configuration = $this
      ->config
      ->get($extractorPluginId . '_configuration');
    $this->extractionMethod = $this
      ->textExtractorPluginManager
      ->createInstance($extractorPluginId, $configuration);

    $this->extractor = $this
      ->processorPluginManager
      ->createInstance('file_attachments');;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $pluginDefinition) {
    return new static(
      $plugin_id,
      $pluginDefinition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('module_handler'),
      $container->get('plugin.manager.search_api.processor'),
      $container->get('plugin.manager.search_api_attachments.text_extractor'),
      $container->get('config.factory')->get(FilesExtrator::CONFIGNAME)
    );
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = array();

    foreach ($this->getEntitiesToView($items, $langcode) as $delta => $file) {
      if ($contents = $this->extractFileContents($file)) {
        $elements[$delta] = array(
          '#markup' => $contents,
          '#cache' => array(
            'tags' => $file->getCacheTags(),
          ),
        );
      }
    }

    return $elements;
  }

  /**
   * Extracts content of given file.
   *
   * @param $file
   *
   * @return string|NULL
   *   Content of the file or NULL if type of file is not supported.
   */
  protected function extractFileContents($file) {
    if ($this->isFileIndexable($file)) {
      return $this
        ->extractor
        ->extractOrGetFromCache($file, $this->extractionMethod);
    }
  }

  /**
   * Check if the file is allowed to be indexed.
   *
   * @param object $file
   *   A file object.
   *
   * @return bool
   *   TRUE or FALSE
   */
  protected function isFileIndexable($file) {
    // This method is a copy of
    // Drupal\search_api_attachments\Plugin\search_api\processor\FilesExtrator::isFileIndexable()
    // and differs mostly in the signature. Unfortunately it can't be used here
    // as it requires second argument of type \Drupal\search_api\Item\ItemInterface.

    // File should exist in disc.
    $indexable = file_exists($file->getFileUri());
    if (!$indexable) {
      return FALSE;
    }
    // File should have a mime type that is allowed.
    $indexable = $indexable && !in_array($file->getMimeType(), $this->extractor->getExcludedMimes());
    if (!$indexable) {
      return FALSE;
    }
    // File permanent.
    $indexable = $indexable && $file->isPermanent();
    if (!$indexable) {
      return FALSE;
    }
    // File shouldn't exceed configured file size.
    $indexable = $indexable && $this->extractor->isFileSizeAllowed($file);
    if (!$indexable) {
      return FALSE;
    }
    // Whether a private file can be indexed or not.
    $indexable = $indexable && $this->extractor->isPrivateFileAllowed($file);
    if (!$indexable) {
      return FALSE;
    }
    $result = $this->moduleHandler->invokeAll(
      'search_api_attachments_indexable',
      array($file)
    );
    $indexable = !in_array(FALSE, $result, TRUE);
    return $indexable;
  }

}
