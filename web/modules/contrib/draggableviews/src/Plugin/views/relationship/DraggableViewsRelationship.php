<?php

namespace Drupal\draggableviews\Plugin\views\relationship;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\ViewsHandlerManager;
use Drupal\views\Plugin\views\relationship\RelationshipPluginBase;
use Drupal\views\Views;

/**
 * A relationship handlers which reverse entity references.
 *
 * @ingroup views_relationship_handlers
 *
 * @ViewsRelationship("draggableviews")
 */
class DraggableViewsRelationship extends RelationshipPluginBase {

  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['source'] = ['default' => ''];
    return $options;
  }

  /**
   * Shortcut to display the value form.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $views = Views::getEnabledViews();
    $draggableviews = ['' => t('Please select')];
    $master = FALSE;


    foreach ($this->view->displayHandlers as $display) {
      /** @var \Drupal\views\Plugin\views\display\DisplayPluginInterface $display */
      if ($display->display['id'] == 'default' && array_key_exists('draggableviews', $display->options['fields'])) {
        $master = TRUE;
      }
      if ($display->display['id'] != 'default' && array_key_exists('draggableviews', $display->options['fields'])) {
        $draggableviews[$this->view->id() . '|' . $display->display['id']] = $this->t('@view (@display)', ['@view' => $this->t('Current view'), '@display' => $display->display['display_title']]);
      }
      if ($master && empty($display->options['fields'])) {
        $draggableviews[$this->view->id() . '|' . $display->display['id']] = $this->t('@view (@display)', ['@view' => $this->t('Current view'), '@display' => $display->display['display_title']]);
      }
    }

    $draggableviews_config = [];
    foreach ($views as $view) {
      if ($view->id() == $this->view->id()) {
        continue;
      }

      $config = \Drupal::config('views.view.' . $view->id());
      $rawData = $config->getRawData();

      $master = FALSE;
      foreach ($rawData['display'] as $display_key => $display) {
        if ($display_key == 'default' && array_key_exists('draggableviews', $display['display_options']['fields'])) {
          $master = TRUE;
        }
        if ($display_key != 'default' && array_key_exists('draggableviews', $display['display_options']['fields'])) {
          $draggableviews_config[$view->id() . '|' . $display_key] = $this->t('@view (@display)', ['@view' => $view->label(), '@display' => $display['display_title']]);
        }
        if ($master && empty($display['display_options']['fields'])) {
          $draggableviews_config[$view->id() . '|' . $display_key] = $this->t('@view (@display)', ['@view' => $view->label(), '@display' => $display['display_title']]);
        }
      }
    }

    asort($draggableviews_config);

    $form['source'] = array(
      '#type' => 'select',
      '#title' => $this->t('Draggableviews source'),
      '#options' => $draggableviews + $draggableviews_config,
      '#default_value' => $this->options['source'],
      '#required' => TRUE,
      '#description' => $this->t('Only views and displays with a draggableview field are displayed here.'),
    );

  }

  /**
   * Called to implement a relationship in a query.
   */
  public function query() {

    if (!empty($this->options['source'])) {
      list($view_name, $view_display) = explode('|', $this->options['source']);
      if (empty($this->definition['extra'])) {
        $this->definition['extra'] = [];
      }
      $this->definition['extra'][] = ['field' => 'view_name', 'value' => $view_name];
      $this->definition['extra'][] = ['field' => 'view_display', 'value' => $view_display];
    }

    return parent::query();
  }

}
