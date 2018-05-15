<?php

namespace Drupal\menu_block\Factory;

use Drupal\menu_block\Plugin\Block\MenuBlock;

interface MenuBlockTitleFactoryInterface {

  /**
   * @param \Drupal\menu_block\Plugin\Block\MenuBlock $menuBlock
   * @return \Drupal\menu_block\Title\MenuBlockTitleInterface
   */
  public function create(MenuBlock $menuBlock);
}
