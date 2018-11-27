<?php

namespace Drupal\getfarmcredit_client\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class BlankPageController.
 */
class BlankPageController extends ControllerBase {

  /**
   * Empty.
   *
   * @return array
   *   Return Empty render array.
   */
  public function empty() {
    $page = [];
    $description = array(
      '#tag' => 'meta',
      '#attributes' => array(
        'name' => 'description',
        'content' => $this->t('Find the Farm Credit location that serves you.'),
      ),
    );

    $page['#attached']['html_head'][] = [$description, 'description'];
    return $page;
  }

}
