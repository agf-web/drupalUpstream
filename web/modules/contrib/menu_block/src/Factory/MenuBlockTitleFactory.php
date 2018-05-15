<?php

namespace Drupal\menu_block\Factory;

use Drupal\Core\Entity\linkManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Menu\MenuActiveTrailInterface;
use Drupal\Core\Menu\MenuLinkManagerInterface;
use Drupal\menu_block\Exception\MenuBlockNonExistingActiveTrailIndex;
use Drupal\menu_block\Plugin\Block\MenuBlock;
use Drupal\menu_block\Title\MenuBlockActiveTrailActiveItemTitle;
use Drupal\menu_block\Title\MenuBlockActiveTrailParentItemTitle;
use Drupal\menu_block\Title\MenuBlockActiveTrailRootItemTitle;
use Drupal\menu_block\Title\MenuBlockBlockTitle;
use Drupal\menu_block\Title\MenuBlockEmptyTitle;
use Drupal\menu_block\Title\MenuBlockInitialMenuItemTitle;
use Drupal\menu_block\Title\MenuBlockMenuTitle;
use Exception;

class MenuBlockTitleFactory implements MenuBlockTitleFactoryInterface {
  /** @var \Drupal\Core\Entity\EntityTypeManagerInterface */
  private $entityTypeManager;
  /** @var \Drupal\Core\Menu\MenuActiveTrailInterface */
  private $menuActiveTrail;
  /** @var \Drupal\Core\Entity\linkManagerInterface */
  private $linkManager;

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param \Drupal\Core\Menu\MenuActiveTrailInterface $menuActiveTrail
   * @param \Drupal\Core\Menu\MenuLinkManagerInterface $linkManager
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, MenuActiveTrailInterface $menuActiveTrail, MenuLinkManagerInterface $linkManager) {
    $this->entityTypeManager = $entityTypeManager;
    $this->menuActiveTrail = $menuActiveTrail;
    $this->linkManager = $linkManager;
  }

  /**
   * @param \Drupal\menu_block\Plugin\Block\MenuBlock $menuBlock
   * @return \Drupal\menu_block\Title\MenuBlockTitleInterface
   */
  public function create(MenuBlock $menuBlock) {
    switch ($this->getLabelType($menuBlock)) {
      case 'menu':
        return new MenuBlockMenuTitle($menuBlock, $this->entityTypeManager);
        break;
      case 'active_item':
        return $this->getActiveTrailActiveItemTitle($menuBlock);
        break;
      case 'parent':
        return $this->getActiveTrailParentItemTitle($menuBlock);
        break;
      case 'root':
        return $this->getActiveTrailRootItemTitle($menuBlock);
        break;
      case 'initial_menu_item':
        return $this->getInitialMenuItemTitle($menuBlock);
        break;
      case 'block':
      default:
        return new MenuBlockBlockTitle($menuBlock);
    }
  }

  /**
   * @param \Drupal\menu_block\Plugin\Block\MenuBlock $menuBlock
   * @return mixed
   */
  private function getLabelType(MenuBlock $menuBlock) {
    $blockConfiguration = $menuBlock->getConfiguration();
    return $blockConfiguration['label_type'];
  }

  /**
   * @param \Drupal\menu_block\Plugin\Block\MenuBlock $menuBlock
   * @return \Drupal\menu_block\Title\MenuBlockTitleInterface
   */
  private function getActiveTrailActiveItemTitle(MenuBlock $menuBlock) {
    try {
      return new MenuBlockActiveTrailActiveItemTitle($menuBlock, $this->menuActiveTrail, $this->linkManager);
    } catch (Exception $exception) {
      return new MenuBlockEmptyTitle();
    }
  }

  /**
   * @param \Drupal\menu_block\Plugin\Block\MenuBlock $menuBlock
   * @return \Drupal\menu_block\Title\MenuBlockTitleInterface
   */
  private function getActiveTrailParentItemTitle(MenuBlock $menuBlock) {
    try {
      return new MenuBlockActiveTrailParentItemTitle($menuBlock, $this->menuActiveTrail, $this->linkManager);
    } catch (MenuBlockNonExistingActiveTrailIndex $exception) {
      return $this->getActiveTrailActiveItemTitle($menuBlock);
    } catch (Exception $exception) {
      return new MenuBlockEmptyTitle();
    }
  }

  /**
   * @param \Drupal\menu_block\Plugin\Block\MenuBlock $menuBlock
   * @return \Drupal\menu_block\Title\MenuBlockTitleInterface
   */
  private function getActiveTrailRootItemTitle(MenuBlock $menuBlock) {
    try {
      return new MenuBlockActiveTrailRootItemTitle($menuBlock, $this->menuActiveTrail, $this->linkManager);
    } catch (Exception $exception) {
      return new MenuBlockEmptyTitle();
    }
  }

  /**
   * @param $menuBlock
   * @return \Drupal\menu_block\Title\MenuBlockTitleInterface
   */
  private function getInitialMenuItemTitle($menuBlock) {
    try {
      return new MenuBlockInitialMenuItemTitle($menuBlock, $this->menuActiveTrail, $this->linkManager);
    } catch (Exception $exception) {
      return new MenuBlockEmptyTitle();
    }
  }

}
