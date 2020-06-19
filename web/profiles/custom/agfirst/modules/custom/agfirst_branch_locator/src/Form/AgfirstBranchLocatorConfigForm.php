<?php

namespace Drupal\agfirst_branch_locator\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Lorem Ipsum block form
 */
class AgfirstBranchLocatorConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'agfirst_branch_locator_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'agfirst_branch_locator.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('agfirst_branch_locator.settings');

    $form['auth_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Auth Key'),
      '#description' => $this->t(''),
      '#default_value' => $config->get('auth_key'),
    ];

    $form['use_drupal_detail_url'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use Drupal detail url?'),
      '#description' => $this->t('If enabled, Branch Locator will check for detail page links from drupal. (only works if detail sub-module is enabled)'),
      '#default_value' => $config->get('use_drupal_detail_url'),
    ];

    $form['disable_logo'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Disable Logo?'),
      '#description' => $this->t(''),
      '#default_value' => $config->get('disable_logo'),
    ];

    $form['disable_title'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Disable Title?'),
      '#description' => $this->t('This hides the \'Find a Location\' text.'),
      '#default_value' => $config->get('disable_title'),
    ];

    $form['custom_logo_enable'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use Custom Logo?'),
      '#description' => $this->t(''),
      '#default_value' => $config->get('custom_logo_enable'),
    ];

    $form['custom_logo_url'] = [
      '#type' => 'url',
      '#title' => $this->t('Custom Logo Url'),
      '#description' => $this->t(''),
      '#default_value' => $config->get('custom_logo_url'),
    ];

    $form['custom_intro_enable'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use Custom Intro Text?'),
      '#description' => $this->t(''),
      '#default_value' => $config->get('custom_intro_enable'),
    ];

    $form['custom_intro_text'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Custom Intro Text'),
      '#description' => $this->t(''),
      '#default_value' => $config->get('custom_intro_text'),
    ];

    $form['filter_by_state'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Filter Branches by State?'),
      '#description' => $this->t(''),
      '#default_value' => $config->get('filter_by_state'),
    ];

    $form['state_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('State to Filter by?'),
      '#description' => $this->t('Use 2 letter abbreviation only.'),
      '#default_value' => $config->get('state_name'),
    ];

    $form['filter_by_association'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Filter by Association?'),
      '#description' => $this->t(''),
      '#default_value' => $config->get('filter_by_association'),
    ];

    $form['association_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Association'),
      '#description' => $this->t(''),
      '#default_value' => $config->get('custom_intro_enable'),
      '#empty_option' => 'Select an Association',
      '#options' => [
        '0' => 'AgCarolina Farm Credit, ACA',
        '1' => 'AgChoice Farm Credit, ACA',
        '2' => 'AgCredit, ACA',
        '3' => 'AgGeorgia Farm Credit, ACA',
        '4' => 'AgSouth Farm Credit, ACA',
        '5' => 'ArborOne Farm Credit, ACA',
        '6' => 'Cape Fear Farm Credit, ACA',
        '7' => 'Carolina Farm Credit, ACA',
        '8' => 'Central Kentucky ACA',
        '9' => 'Colonial Farm Credit',
        '10' => 'Farm Credit of Central Florida, ACA',
        '11' => 'Farm Credit of Florida, ACA',
        '12' => 'Farm Credit of Northwest Florida, ACA',
        '13' => 'Farm Credit of the Virginias, ACA',
        '14' => 'First South ACA',
        '15' => 'MidAtlantic Farm Credit, ACA',
        '16' => 'River Valley AgCredit',
        '17' => 'Southwest Georgia Farm Credit, ACA',
        '18' => 'Yankee Farm Credit ACA',
      ]
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('agfirst_branch_locator.settings')
      ->set('auth_key', $values['auth_key'])
      ->set('use_drupal_detail_url', $values['use_drupal_detail_url'])
      ->set('disable_logo', $values['disable_logo'])
      ->set('disable_title', $values['disable_title'])
      ->set('custom_logo_enable', $values['custom_logo_enable'])
      ->set('custom_logo_url', $values['custom_logo_url'])
      ->set('custom_intro_enable', $values['custom_intro_enable'])
      ->set('custom_intro_text', $values['custom_intro_text'])
      ->set('filter_by_state', $values['filter_by_state'])
      ->set('state_name', $values['state_name'])
      ->set('filter_by_association', $values['filter_by_association'])
      ->set('association_id', $values['association_id'])
      ->save();
    parent::submitForm($form, $form_state);
  }
}
