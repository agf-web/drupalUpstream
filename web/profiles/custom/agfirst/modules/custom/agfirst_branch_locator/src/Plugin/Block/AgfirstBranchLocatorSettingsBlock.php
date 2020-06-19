<?php

/**
 * @file
 * Contains \Drupal\agfirst_branch_locator_details\Plugin\Block\AgfirstBranchLocatorSettingsBlock.
 */

namespace Drupal\agfirst_branch_locator\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Cache\Cache;

/**
 * Provides a 'AgfirstBranchLocatorSettingsBlock' block.
 *
 * @Block(
 *  id = "agfirst_branch_locator_settings_block",
 *  admin_label = @Translation("AgFirst Branch Locator - App Settings Block"),
 * )
 */
class AgfirstBranchLocatorSettingsBlock extends BlockBase implements BlockPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $block = [
      '#type' => 'html_tag',
      '#tag' => 'script',
      '#value' => $this->_getConfig(),
      '#attributes' => [
        'class' => ['agfirst-branch-locator-settings-block'],
        'id' => 'agfirst-branch-locator-settings-block'
      ],
      '#cache' => [
        'max-age' => 0,
      ],
    ];

    $build['agfirst_branch_locator_settings_block'] = $block;
    return $build;
  }

  private function _getConfig() {

    $config = \Drupal::config('agfirst_branch_locator.settings');

    $authKey = $config->get('auth_key');
    $useDrupalDetailUrl = $config->get('use_drupal_detail_url');
    $disableLogo = $config->get('disable_logo');
    $disableTitle = $config->get('disable_title');
    $customLogoEnable = $config->get('custom_logo_enable');
    $customLogoUrl = $config->get('custom_logo_url');
    $customIntroEnable = $config->get('custom_intro_enable');
    $customIntroText = $config->get('custom_intro_text');
    $filterByState = $config->get('filter_by_state');
    $stateName = $config->get('state_name');
    $filterByAssociation = $config->get('filter_by_association');
    $associationId = $config->get('association_id');

    // funky formatting, but required for the multi-line string.
    $str = <<<EOD
window.AGF_CONFIG = {
  authKey: '$authKey',
  useDrupalDetailUrl: $useDrupalDetailUrl,
  disableLogo: $disableLogo,
  disableTitle: $disableTitle,
  customLogo: {
    useCustomLogo: $customLogoEnable,
    url: '$customLogoUrl',
  },
  customIntro: {
    useCustomIntro: $customIntroEnable,
    text: '$customIntroText',
  },
  byState: $filterByState,
  stateName: '$stateName',
  byAssociation: $filterByAssociation,
  associationName: function () {
    return this.associations[$associationId];
  },
  associations: [
    'AgCarolina Farm Credit, ACA',              // 0
    'AgChoice Farm Credit, ACA',                // 1
    'AgCredit, ACA',                            // 2
    'AgGeorgia Farm Credit, ACA',               // 3
    'AgSouth Farm Credit, ACA',                 // 4
    'ArborOne Farm Credit, ACA',                // 5
    'Cape Fear Farm Credit, ACA',               // 6
    'Carolina Farm Credit, ACA',                // 7
    'Central Kentucky ACA',                     // 8
    'Colonial Farm Credit',                     // 9
    'Farm Credit of Central Florida, ACA',      // 10
    'Farm Credit of Florida, ACA',              // 11
    'Farm Credit of Northwest Florida, ACA',    // 12
    'Farm Credit of the Virginias, ACA',        // 13
    'First South ACA',                          // 14
    'MidAtlantic Farm Credit, ACA',             // 15
    'River Valley AgCredit',                    // 16
    'Southwest Georgia Farm Credit, ACA',       // 17
    'Yankee Farm Credit ACA'                    // 18
  ]
};
EOD;

    return $str;
  }
}
