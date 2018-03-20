<?php

namespace Drupal\cyberwoven_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Cache\Cache;

/**
 * Provides a 'SocialMediaBlock' block.
 *
 * @Block(
 *  id = "social_media_block",
 *  admin_label = @Translation("Social media block"),
 * )
 */
class SocialMediaBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'service_one' => $this->t(''),
      'service_two' => $this->t(''),
      'service_three' => $this->t(''),
      'service_four' => $this->t(''),
      'url_one' => $this->t(''),
      'url_two' => $this->t(''),
      'url_three' => $this->t(''),
      'url_four' => $this->t(''),
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['one'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Service One'),
    ];
    $form['two'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Service Two'),
    ];
    $form['three'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Service Three'),
    ];
    $form['four'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Service Four'),
    ];
    $form['one']['service_one'] = [
      '#type' => 'select',
      '#title' => $this->t('Service'),
      '#description' => $this->t('The service this link is for (sets icon).'),
      '#options' => $this->validServices(),
      '#default_value' => $this->configuration['service_one'],
      '#size' => 1,
      '#weight' => '1',
      '#multiple' => FALSE,
    ];
    $form['one']['url_one'] = [
      '#type' => 'url',
      '#title' => $this->t('Link'),
      '#description' => $this->t('The URL for your account this service.'),
      '#default_value' => $this->configuration['url_one'],
      '#weight' => '1',
    ];
    $form['two']['service_two'] = [
      '#type' => 'select',
      '#title' => $this->t('Service'),
      '#description' => $this->t('The service this link is for (sets icon).'),
      '#options' => $this->validServices(),
      '#default_value' => $this->configuration['service_two'],
      '#size' => 1,
      '#weight' => '10',
      '#multiple' => FALSE,
    ];
    $form['two']['url_two'] = [
      '#type' => 'url',
      '#title' => $this->t('Link'),
      '#description' => $this->t('The URL for your account this service.'),
      '#default_value' => $this->configuration['url_two'],
      '#weight' => '10',
    ];
    $form['three']['service_three'] = [
      '#type' => 'select',
      '#title' => $this->t('Service'),
      '#description' => $this->t('The service this link is for (sets icon).'),
      '#options' => $this->validServices(),
      '#default_value' => $this->configuration['service_three'],
      '#size' => 1,
      '#weight' => '20',
      '#multiple' => FALSE,
    ];
    $form['three']['url_three'] = [
      '#type' => 'url',
      '#title' => $this->t('Link'),
      '#description' => $this->t('The URL for your account this service.'),
      '#default_value' => $this->configuration['url_three'],
      '#weight' => '20',
    ];
    $form['four']['service_four'] = [
      '#type' => 'select',
      '#title' => $this->t('Service'),
      '#description' => $this->t('The service this link is for (sets icon).'),
      '#options' => $this->validServices(),
      '#default_value' => $this->configuration['service_four'],
      '#size' => 1,
      '#weight' => '30',
      '#multiple' => FALSE,
    ];
    $form['four']['url_four'] = [
      '#type' => 'url',
      '#title' => $this->t('Link'),
      '#description' => $this->t('The URL for your account this service.'),
      '#default_value' => $this->configuration['url_four'],
      '#weight' => '30',
    ];

    return $form;
  }

  /**
   * Provides a list of services that are valid for this module.
   *
   * The array provided is used for the option list and for selects and for
   * validation.
   *
   * @returns array
   *   An array of options.
   */
  private function validServices() {
    return [
      'n/a' => $this->t('None'),
      'facebook' => $this->t('Facebook'),
      'twitter' => $this->t('Twitter'),
      'youtube' => $this->t('YouTube'),
      'pintrest' => $this->t('Pintrest'),
      'flickr' => $this->t('Flickr'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockValidate($form, FormStateInterface $form_state) {
    $options = array_keys($this->validServices());

    $values = [
      'Service One' => $form_state->getValue('one')['service_one'],
      'Service Two' => $form_state->getValue('two')['service_two'],
      'Service Three' => $form_state->getValue('three')['service_three'],
      'Service Four' => $form_state->getValue('four')['service_four'],
    ];

    foreach ($values as $name => $value) {
      if (!in_array($value, $options)) {
        $form_state->setError($this->t('Invalid service value for @service', ['@service' => $name]));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['service_one'] = $form_state->getValue('one')['service_one'] != 'n/a' ? $form_state->getValue('one')['service_one'] : '';
    $this->configuration['service_two'] = $form_state->getValue('two')['service_two'] != 'n/a' ? $form_state->getValue('two')['service_two'] : '';
    $this->configuration['service_three'] = $form_state->getValue('three')['service_three'] != 'n/a' ? $form_state->getValue('three')['service_three'] : '';
    $this->configuration['service_four'] = $form_state->getValue('four')['service_four'] != 'n/a' ? $form_state->getValue('four')['service_four'] : '';
    $this->configuration['url_one'] = $form_state->getValue('one')['url_one'];
    $this->configuration['url_two'] = $form_state->getValue('two')['url_two'];
    $this->configuration['url_three'] = $form_state->getValue('three')['url_three'];
    $this->configuration['url_four'] = $form_state->getValue('four')['url_four'];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return Cache::mergeTags(parent::getCacheTags(), ['social_media_block']);
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['social_media_block_service_one'] = [
      '#theme' => 'cyberwoven_blocks_social_media',
      '#attributes' => [
        'class' => ['social-media-links'],
        'id' => 'cyberwoven-social-media-block',
      ],
      '#services' => [
        'one' => $this->configuration['service_one'],
        'two' => $this->configuration['service_two'],
        'three' => $this->configuration['service_three'],
        'four' => $this->configuration['service_four'],
      ],
      '#urls' => [
        'one' => $this->configuration['url_one'],
        'two' => $this->configuration['url_two'],
        'three' => $this->configuration['url_three'],
        'four' => $this->configuration['url_four'],
      ],
      '#cache' => [
        'tags' => ['social-media'],
      ],
    ];

    return $build;
  }

}
