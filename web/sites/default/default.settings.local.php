<?php

$databases['default']['default'] = array (
  'database' => '',
  'username' => '',
  'password' => '',
  'prefix' => '',
  'host' => 'localhost',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

// Names and colors for EI in Sandbox.
$config['environment_indicator.indicator']['name'] = 'Sandbox';
$config['environment_indicator.indicator']['bg_color'] = '#48484a';
$config['environment_indicator.indicator']['fg_color'] = '#ffffff';

//$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';

$settings['trusted_host_patterns'] = array(
  '^.+\.pantheon$',
);

//$settings['cache']['bins']['render'] = 'cache.backend.null';
//$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;

$settings['hash_salt'] = "ouyiasdfpouyasdfpysdkhfjsa0d78as8fyasdfhasdklfhjaskdljfhsa";

/**
 * Show all error messages, with backtrace information.
 *
 * In case the error level could not be fetched from the database, as for
 * example the database connection failed, we rely only on this value.
 */
$config['system.logging']['error_level'] = 'verbose';

/**
 * Disable CSS and JS aggregation.
 */
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;

/**
 * Skip file system permissions hardening.
 *
 * The system module will periodically check the permissions of your site's
 * site directory to ensure that it is not writable by the website user. For
 * sites that are managed with a version control system, this can cause problems
 * when files in that directory such as settings.php are updated, because the
 * user pulling in the changes won't have permissions to modify files in the
 * directory.
 */
$settings['skip_permissions_hardening'] = TRUE;
