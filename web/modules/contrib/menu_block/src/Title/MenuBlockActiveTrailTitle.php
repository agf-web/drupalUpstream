<?php

namespace Drupal\menu_block\Title;

use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Menu\MenuLinkManagerInterface;
use Drupal\Core\Menu\MenuActiveTrailInterface;
use Drupal\menu_block\Exception\MenuBlockNoActiveTrailFoundException;
use Drupal\menu_block\Exception\MenuBlockNonExistingActiveTrailIndex;
use Drupal\menu_block\Plugin\Block\MenuBlock;

abstract class MenuBlockActiveTrailTitle {
  /** @var \Drupal\menu_block\Plugin\Block\MenuBlock */
  protected $menuBlock;
  /** @var \Drupal\Core\Menu\MenuActiveTrailInterface */
  private $menuActiveTrail;
  /** @var \Drupal\Core\Menu\MenuLinkManagerInterface */
  protected $linkManager;
  /** @var string */
  protected $label;

  /**
   * @param \Drupal\menu_block\Plugin\Block\MenuBlock $menuBlock
   * @param \Drupal\Core\Menu\MenuActiveTrailInterface $menuActiveTrail
   * @param \Drupal\Core\Menu\MenuLinkManagerInterface $linkManager
   * @throws \Drupal\menu_block\Exception\MenuBlockNoActiveTrailFoundException
   */
  public function __construct(MenuBlock $menuBlock, MenuActiveTrailInterface $menuActiveTrail, MenuLinkManagerInterface $linkManager) {
    $this->menuBlock = $menuBlock;
    $this->menuActiveTrail = $menuActiveTrail;
    $this->linkManager = $linkManager;
    $this->setLabel();
  }

  /**
   * @return string
   */
  public function label() {
    return $this->label;
  }

  /**
   * @return string
   * @throws \Drupal\menu_block\Exception\MenuBlockNoActiveTrailFoundException
   * @throws \Drupal\menu_block\Exception\MenuBlockNonExistingActiveTrailIndex
   */
  protected function setLabel() {
    if ($activeTrailIds = $this->getDerivativeActiveTrailIds()) {
      if ($initialMenuLevelItem = array_slice($activeTrailIds, $this->getItemLevel() - 1, 1)) {
        $this->label = $this->getMenuLinkTitleByPluginId(reset($initialMenuLevelItem));
      }
      else {
        throw new MenuBlockNonExistingActiveTrailIndex("There was no item with a starting level of {$this->getItemLevel()} found in the active trail.");
      }
    }
    else {
      throw new MenuBlockNoActiveTrailFoundException("The current active trail could not be determined.");
    }
  }

  /**
   * @return int
   */
  abstract protected function getItemLevel();

  /**
   * @return array
   */
  protected function getDerivativeActiveTrailIds() {
    $menuId = $this->menuBlock->getDerivativeId();
    return array_filter($this->menuActiveTrail->getActiveTrailIds($menuId));
  }

  /**
   * @param string $pluginId
   * @return string
   */
  protected function getMenuLinkTitleByPluginId($pluginId) {
    if ($menuLink = $this->linkManager->createInstance($pluginId)) {
      return $menuLink->getTitle();
    }

    return '';
  }

}
