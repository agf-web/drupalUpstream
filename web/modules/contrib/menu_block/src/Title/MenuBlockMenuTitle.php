<?php

namespace Drupal\menu_block\Title;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\menu_block\Plugin\Block\MenuBlock;

class MenuBlockMenuTitle implements MenuBlockTitleInterface {
  /** @var \Drupal\menu_block\Plugin\Block\MenuBlock */
  private $menuBlock;
  /** @var \Drupal\Core\Entity\EntityStorageInterface */
  private $menuStorage;

  /**
   * @param \Drupal\menu_block\Plugin\Block\MenuBlock $menuBlock
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   */
  public function __construct(MenuBlock $menuBlock, EntityTypeManagerInterface $entityTypeManager) {
    $this->menuBlock = $menuBlock;
    $this->menuStorage = $entityTypeManager->getStorage('menu');
  }

  /**
   * @return string
   */
  public function label() {
    $menu = $this->menuStorage->load($this->menuBlock->getDerivativeId());
    return $menu->label();
  }
}
