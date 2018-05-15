<?php

namespace Drupal\menu_block\Title;

class MenuBlockActiveTrailRootItemTitle extends MenuBlockActiveTrailTitle implements MenuBlockTitleInterface {

  /**
   * Reverse the active trail so that the root item will be on top.
   *
   * @return array
   */
  protected function getDerivativeActiveTrailIds() {
    $activeTrailIds = parent::getDerivativeActiveTrailIds();
    return array_reverse($activeTrailIds);
  }

  /**
   * @return int
   */
  protected function getItemLevel() {
    return 1;
  }
}
