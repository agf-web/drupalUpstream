<?php

/**
 * Load services definition file.
 */
$settings['container_yamls'][] = __DIR__ . '/services.yml';

/**
 * Include the Pantheon-specific settings file.
 *
 * n.b. The settings.pantheon.php file makes some changes
 *      that affect all envrionments that this site
 *      exists in.  Always include this file, even in
 *      a local development environment, to ensure that
 *      the site settings remain consistent.
 */
include __DIR__ . "/settings.pantheon.php";

/**
 * Place the config directory outside of the Drupal root.
 */
$config_directories = array(
  CONFIG_SYNC_DIRECTORY => dirname(DRUPAL_ROOT) . '/config',
);

/**
 * Setup Environment Indicator.
 */
if (!defined('PANTHEON_ENVIRONMENT')) {
  $config['environment_indicator.indicator']['name'] = 'Local';
  $config['environment_indicator.indicator']['bg_color'] = '#48484a';
  $config['environment_indicator.indicator']['fg_color'] = '#ffffff';
}
// Pantheon Env Specific Config.
if (isset($_ENV['PANTHEON_ENVIRONMENT'])) {
  switch ($_ENV['PANTHEON_ENVIRONMENT']) {
    case 'dev':
      $config['environment_indicator.indicator']['name'] = 'Dev';
      $config['environment_indicator.indicator']['bg_color'] = '#d25e0f';
      $config['environment_indicator.indicator']['fg_color'] = '#ffffff';
      break;

    case 'test':
      $config['environment_indicator.indicator']['name'] = 'Test';
      $config['environment_indicator.indicator']['bg_color'] = '#f05023';
      $config['environment_indicator.indicator']['fg_color'] = '#ffffff';
      break;

    case 'live':
      $config['environment_indicator.indicator']['name'] = 'Live';
      $config['environment_indicator.indicator']['bg_color'] = '#808285';
      $config['environment_indicator.indicator']['fg_color'] = '#ffffff';
      break;

  }
}

/**
 * If there is a local settings file, then include it
 */
$local_settings = __DIR__ . "/settings.local.php";
if (file_exists($local_settings)) {
  include $local_settings;
}

/**
 * Always install the 'standard' profile to stop the installer from
 * modifying settings.php.
 */
$settings['install_profile'] = 'agfirst';
