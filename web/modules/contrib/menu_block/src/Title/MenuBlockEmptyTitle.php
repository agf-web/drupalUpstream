<?php

namespace Drupal\menu_block\Title;

class MenuBlockEmptyTitle implements MenuBlockTitleInterface {

  /**
   * @return string
   */
  public function label() {
    return '';
  }
}
