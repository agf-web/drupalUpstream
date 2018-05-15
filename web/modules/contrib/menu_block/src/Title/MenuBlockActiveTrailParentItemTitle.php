<?php

namespace Drupal\menu_block\Title;

use Drupal\menu_block\Exception\MenuBlockNoActiveTrailFoundException;
use Drupal\menu_block\Exception\MenuBlockNonExistingActiveTrailIndex;

class MenuBlockActiveTrailParentItemTitle extends MenuBlockActiveTrailTitle implements MenuBlockTitleInterface {

  /**
   * @return int
   */
  protected function getItemLevel() {
    return 2;
  }
}
