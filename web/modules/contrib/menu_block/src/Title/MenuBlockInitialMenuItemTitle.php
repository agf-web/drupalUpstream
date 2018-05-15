<?php

namespace Drupal\menu_block\Title;

class MenuBlockInitialMenuItemTitle extends MenuBlockActiveTrailRootItemTitle implements MenuBlockTitleInterface {

  /**
   * @return int
   */
  protected function getItemLevel() {
    $configuration = $this->menuBlock->getConfiguration();
    return (int) $configuration['level'];
  }
}
