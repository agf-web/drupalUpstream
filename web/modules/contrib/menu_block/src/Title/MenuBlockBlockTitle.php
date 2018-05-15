<?php

namespace Drupal\menu_block\Title;

use Drupal\menu_block\Plugin\Block\MenuBlock;

class MenuBlockBlockTitle implements MenuBlockTitleInterface {
  /** @var \Drupal\menu_block\Plugin\Block\MenuBlock */
  private $menuBlock;

  /**
   * @param \Drupal\menu_block\Plugin\Block\MenuBlock $menuBlock
   */
  public function __construct(MenuBlock $menuBlock) {
    $this->menuBlock = $menuBlock;
  }

  /**
   * @return string
   */
  public function label() {
    return $this->menuBlock->label();
  }
}
