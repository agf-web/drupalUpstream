<?php

/**
 * @file
 * Contains \Drupal\agfirst_careers\Plugin\Block\JobsBlock.
 */

namespace Drupal\agfirst_careers\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;

/**
 * Provides a 'JobsBlock' block.
 *
 * @Block(
 *  id = "jobs_block",
 *  admin_label = @Translation("Jobs Block"),
 * )
 */
class JobsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $client = \Drupal::httpClient();
    $config = \Drupal::config('agfirst_careers.settings');
    $rss_url = $config->get('rss_url');

    $jobs = [];

    try {
        $response = $client->get($rss_url);
        $rss = simplexml_load_string($response->getBody()); 
        $jobs = $rss->channel->item;

        $acceptable_tags = '<a> <b> <strong> <ul> <li> <i> <em> <br> <p>';

        foreach($jobs as $job) {
          $job->description = strip_tags(html_entity_decode($job->description), $acceptable_tags);
        }

    } catch (RequestException $e) {
        watchdog_exception('agfirst_careers', $e->getMessage());
    }

    $no_jobs_message = $config->get('no_jobs_message', 'We have no current job openings, please check back later!');

    return ['agfirst_careers_jobs_block' => [
      '#jobs'  => $jobs,
      '#no_jobs_message' => $no_jobs_message,
      '#theme' => 'agfirst_careers_jobs_block',
      '#cache' => [
        'max-age' => 6 * 60 * 60, // block's rendered jobs list lives for 6 hrs, or until a cache clear
      ],
    ]];
  }
}
