<?php

namespace Drupal\agfirst_branch_locator\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Block\Plugin\Block\Broken;

/**
 * Provides a 'AgfirstBranchLocatorBlock' block.
 *
 * @Block(
 *  id = "agfirst_branch_locator",
 *  admin_label = @Translation("AgFirst Branch Locator"),
 * )
 */
class AgfirstBranchLocatorBlock extends BlockBase implements BlockPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $clientAppDataRenderArray = null;

    // this custom block is installed via the sub-module `agfirst_branch_locator_details`.
    $clientAppDataBlockPlugin = \Drupal::service('plugin.manager.block')->createInstance('agfirst_branch_locator_details_client_app_data_block', []);
    $isNotBroken = !($clientAppDataBlockPlugin instanceof Broken);

    $jsSettingsPlugin = \Drupal::service('plugin.manager.block')->createInstance('agfirst_branch_locator_settings_block', []);
    $jsSettingsRenderArray = $jsSettingsPlugin->build();

    if (isset($clientAppDataBlockPlugin) && !empty($clientAppDataBlockPlugin) && $isNotBroken) {
      $clientAppDataRenderArray = $clientAppDataBlockPlugin->build();
    }

    $block = [
      '#type' => 'inline_template',
      '#template' => '{% if client_app_data_block %}{{client_app_data_block}}{% endif %} {{branch_locator_settings}}<div id="app"></div>',
      '#attributes' => ['id' => 'block-agfirst-branch-locator'],
      'variables' => [
        'client_app_data_block' => $clientAppDataRenderArray,
        'branch_locator_settings' => $jsSettingsRenderArray
      ],
      '#attached' => [
        'library' => [
          'agfirst_branch_locator/agfirst_branch_locator.client'
        ]
      ],
    ];

    $build['agfirst_branch_locator'] = $block;

    return $build;
  }

}
