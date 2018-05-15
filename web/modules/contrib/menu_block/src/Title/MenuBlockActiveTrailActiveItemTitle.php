<?php

namespace Drupal\menu_block\Title;

class MenuBlockActiveTrailActiveItemTitle extends MenuBlockActiveTrailTitle implements MenuBlockTitleInterface {

  /**
   * @return int
   */
  protected function getItemLevel() {
    return 1;
  }
}
