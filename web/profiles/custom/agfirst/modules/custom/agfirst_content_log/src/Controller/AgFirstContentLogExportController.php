<?php

namespace Drupal\agfirst_content_log\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;

/**
 * Class AgFirstContentLogExportController
 *
 * @package Drupal\agfirst_content_log\Controller
 */
class AgFirstContentLogExportController extends ControllerBase {

  /**
   * @return mixed
   */
  public function content() {

    $config = $this->config('agfirst_content_log.settings');

    $header = [
      ['data' => t('Entity ID'), 'field' => 'acl.entity_id'],
      ['data' => t('Title'), 'field' => 'acl.entity_title'],
      ['data' => t('Entity Type'), 'field' => 'acl.entity_type'],
      ['data' => t('Action'), 'field' => 'acl.action'],
      ['data' => t('Author'), 'field' => 'ufd.name'],
      ['data' => t('IP Address'), 'field' => 'acl.client_ip'],
      ['data' => t('Updated'), 'field' => 'acl.timestamp', 'sort' => 'desc'],
    ];

    $query = \Drupal::database()->select('agfirst_content_log', 'acl')
      ->extend('Drupal\Core\Database\Query\TableSortExtender');

    $query->join('users_field_data', 'ufd', 'acl.uid = ufd.uid');
    $query->fields('acl', [
      'lid',
      'uid',
      'timestamp',
      'client_ip',
      'entity_type',
      'entity_id',
      'entity_title',
      'entity_bundle',
      'action'
    ]);
    $query->fields('ufd', [
      'name'
    ]);
    $query->orderByHeader($header);

    $page_rowcount = (($config->get('acl_rowcount')) ?: 50);
    $pager = $query
      ->extend('Drupal\Core\Database\Query\PagerSelectExtender')
      ->limit($page_rowcount);

    $results = $pager->execute()->fetchAll();

    $nids = [];
    $fids = [];
    foreach ($results as $result) {
      if ($result->entity_type == 'node' && !in_array($result->entity_id, $nids)) {
        $nids[] = $result->entity_id;
      }
      if ($result->entity_type == 'file' && !in_array($result->entity_id, $fids)) {
        $fids[] = $result->entity_id;
      }
    }

    $nodes = Node::loadMultiple($nids);
    $files = File::loadMultiple($fids);

    $rows = [];
    foreach ($results as $result) {

      $entity_link = NULL;

      if ($result->entity_type == 'node') {
        if (array_key_exists($result->entity_id, $nodes)) {
          $entity_link = Link::fromTextAndUrl(
            $result->entity_title,
            Url::fromUserInput('/node/' . $result->entity_id)
          );
        }
      }elseif ($result->entity_type == 'file') {
        if (array_key_exists($result->entity_id, $files)) {
          $entity_link = Link::fromTextAndUrl(
            $result->entity_title,
            Url::fromUri($files[$result->entity_id]->createFileUrl(FALSE))
          );
        }
      }


      $user_link = Link::fromTextAndUrl(
        $result->name,
        Url::fromUserInput('/user/' . $result->uid)
      );

      $rows[] = ['data' => [
        'acl.entity_id' => $result->entity_id,
        'acl.title' => (($entity_link) ?: $result->entity_title),
        'acl.entity_type' => $result->entity_type,
        'acl.action' => $result->action,
        'ufd.name' => $user_link,
        'acl.client_ip' => $result->client_ip,
        'acl.timestamp' => \Drupal::service('date.formatter')
          ->format($result->timestamp, 'short'),
      ]];

    }

    $build['table'] = [
      '#type' => 'table', // '#type' => 'tableselect',
      '#header' => $header,
      '#rows' => $rows,   // '#options' => $rows,
      '#empty' => t('No log entries found.'),
    ];

    $build['pager'] = array(
      '#type' => 'pager'
    );

    return $build;
  }

  public function export_csv() {

    $config = $this->config('agfirst_content_log.settings');

    $query = \Drupal::database()->select('agfirst_content_log', 'acl');

    $query->join('users_field_data', 'ufd', 'acl.uid = ufd.uid');
    $query->fields('acl', [
      'lid',
      'uid',
      'timestamp',
      'client_ip',
      'entity_type',
      'entity_title',
      'entity_id',
      'entity_bundle',
      'action'
    ]);
    $query->fields('ufd', [
      'name'
    ]);

    $results = $query->execute()->fetchAll();

    $header = [
      'lid',
      'uid',
      'username',
      'timestamp',
      'client_ip',
      'entity_id',
      'entity_title',
      'entity_type',
      'entity_bundle',
      'entity_url',
      'action',
    ];

    $nids = [];
    $fids = [];
    foreach ($results as $result) {
      if ($result->entity_type == 'node' && !in_array($result->entity_id, $nids)) {
        $nids[] = $result->entity_id;
      }
      if ($result->entity_type == 'file' && !in_array($result->entity_id, $fids)) {
        $fids[] = $result->entity_id;
      }
    }

    $nodes = Node::loadMultiple($nids);
    $files = File::loadMultiple($fids);

    $rows[] = $header; // Set first row to header.

    foreach ($results as $result) {

      $entity_url = NULL;
      if ($result->entity_type == 'node') {
        if (array_key_exists($result->entity_id, $nodes)) {
          $entity_url = Url::fromUserInput('/node/' . $result->entity_id, [
            'absolute' => TRUE
          ])->toString();
        }
      }elseif ($result->entity_type == 'file') {
        if (array_key_exists($result->entity_id, $files)) {
          $entity_url = $files[$result->entity_id]->createFileUrl(FALSE);
        }
      }

      $rows[] = [
        'lid' => $result->lid,
        'uid' => $result->uid,
        'username' => $result->name,
        'timestamp' => \Drupal::service('date.formatter')->format($result->timestamp, 'short'),
        'client_ip' => $result->client_ip,
        'entity_id' => $result->entity_id,
        'entity_title' => $result->entity_title,
        'entity_type' => $result->entity_type,
        'entity_bundle' => $result->entity_bundle,
        'entity_url' => $entity_url,
        'action' => $result->action,
      ];
    }

    // Replace tokens in CSV Filename (if they have been set).
    $csv_filename = \Drupal::service('token')->replace(($config->get('acl_csv_filename')) ?: 'content-log.csv');

    // Store csv string in variable.
    $handle = fopen('php://memory', 'w');
    foreach( $rows as $offset => $row) {
      fputcsv($handle, (array) $row, ',', '"');
    }
    fseek($handle, 0);

    // Get the CSV string.
    $csv = stream_get_contents($handle);
    $csv = mb_convert_encoding($csv, 'iso-8859-2', 'utf-8');

    // Prepare the directory (in case it doesn't exist), and save the file.
    $directory = 'public://content_logs/';
    \Drupal::service('file_system')->prepareDirectory($directory, \Drupal\Core\File\FileSystem::CREATE_DIRECTORY);
    $file = file_save_data($csv,  $directory . $csv_filename, FileSystemInterface::EXISTS_REPLACE);

    \Drupal::messenger()->addStatus(t('The CSV was created: <a href=":csv_url?cb=:cachebuster">@csv_filename</a>.', [
      ':csv_url' => file_create_url($directory . $csv_filename),
      ':cachebuster' => time(),
      '@csv_filename' => $csv_filename,
    ]));

    return $this->redirect('agfirst_content_log.display');
  }

}
