<?php

namespace Drupal\cyberwoven_blocks\Plugin\Condition;

use Drupal\node\Plugin\Condition\NodeType;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Node Type' condition to doesn't block on non-nodes
 *
 * @Condition(
 *   id = "cyberwoven_node_type",
 *   label = @Translation("Non-blocking Node Bundle"),
 *   context = { }
 * )
 */
class CyberwovenNodeType extends NodeType {

  /**
   * Current route matcher.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $currentRouteMatch;

  /**
   * Creates a new NodeType instance.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $current_route_match
   *   The current route match interface.
   * @param \Drupal\Core\Entity\EntityStorageInterface $entity_storage
   *   The entity storage.
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(RouteMatchInterface $current_route_match, EntityStorageInterface $entity_storage, array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($entity_storage, $configuration, $plugin_id, $plugin_definition);
    $this->currentRouteMatch = $current_route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('current_route_match'),
      $container->get('entity.manager')->getStorage('node_type'),
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $options = [];
    $node_types = $this->entityStorage->loadMultiple();
    foreach ($node_types as $type) {
      $options[$type->id()] = $type->label();
    }
    $form['bundles'] = [
      '#title' => $this->t('Node types'),
      '#type' => 'checkboxes',
      '#options' => $options,
      '#default_value' => $this->configuration['bundles'],
    ];

    $all_others = 0;
    if (!empty($this->configuration['all_others'])) {
      $all_others = $this->configuration['all_others'];
    }

    $form['all_others'] = [
      '#title' => $this->t('Include on all non-node Pages'),
      '#type' => 'checkbox',
      '#default_value' => $all_others,
    ];

    return parent::buildConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['bundles'] = array_filter($form_state->getValue('bundles'));
    $this->configuration['all_others'] = $form_state->getValue('all_others');
    parent::submitConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {

    $others = '';
    if (!empty($this->configuration['all_others'])) {
      $others = $this->t('and all other pages.');
    }

    if (count($this->configuration['bundles']) > 1) {
      $bundles = $this->configuration['bundles'];
      $last = array_pop($bundles);
      $bundles = implode(', ', $bundles);
      return $this->t('The node bundle is @bundles or @last @others',
        [
          '@bundles' => $bundles,
          '@last' => $last,
          '@others' => $others,
        ]);
    }
    $bundle = reset($this->configuration['bundles']);
    return $this->t('The node bundle is @bundle @others',
      [
        '@bundle' => $bundle,
        '@others' => $others,
      ]);
  }

  /**
   * {@inheritdoc}
   */
  public function evaluate() {
    if (empty($this->configuration['bundles']) && !$this->isNegated()) {
      return TRUE;
    }

    $routeName = $this->currentRouteMatch->getRouteName();
    if ($routeName == 'entity.node.canonical') {
      $node = $this->currentRouteMatch->getParameter('node');
      return !empty($this->configuration['bundles'][$node->getType()]);
    }

    $all_others = 0;
    if (!empty($this->configuration['all_others'])) {
      $all_others = (bool) $this->configuration['all_others'];
    }

    return $all_others;

  }

}
