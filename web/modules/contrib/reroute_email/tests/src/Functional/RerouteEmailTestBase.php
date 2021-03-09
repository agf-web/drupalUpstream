<?php

namespace Drupal\Tests\reroute_email\Functional;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Test\AssertMailTrait;
use Drupal\Tests\BrowserTestBase;

/**
 * @defgroup reroute_email_tests Test Suit
 *
 * @{
 * The automated test suit for Reroute Email.
 *
 * @}
 */

/**
 * Base test class for Reroute Email test cases.
 */
abstract class RerouteEmailTestBase extends BrowserTestBase {

  use AssertMailTrait;

  /**
   * An editable config.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $rerouteConfig;

  /**
   * An array of helper modules for the reroute email tests.
   *
   * @var array
   */
  public static $modules = ['reroute_email'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * User object to perform site browsing.
   *
   * @var \Drupal\user\Entity\User
   */
  protected $adminUser;

  /**
   * Original email address used for the tests.
   *
   * @var string
   */
  protected $originalDestination = 'original@example.com';

  /**
   * Reroute email destination address used for the tests.
   *
   * @var string
   */
  protected $rerouteDestination = 'rerouted@example.com';

  /**
   * Whitelisted domain destination address used for the tests.
   *
   * @var string
   */
  protected $whitelistedDomain = '*@example.com';

  /**
   * Permissions required by the user to perform the tests.
   *
   * @var array
   */
  protected $permissions = [
    'administer reroute email',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->rerouteConfig = $this->config('reroute_email.settings');

    // Authenticate test user.
    $this->adminUser = $this->drupalCreateUser($this->permissions);
    $this->drupalLogin($this->adminUser);
  }

  /**
   * Helper function to configure Reroute Email Settings.
   *
   * @param bool $enable
   *   (optional) Set to TRUE to enable email Rerouting.
   * @param string $addresses
   *   (optional) The email addresses to which emails should be rerouted.
   * @param string $whitelisted
   *   (optional) The whitelisted email addresses.
   * @param bool $description
   *   (optional) Set to TRUE to show rerouting description.
   * @param bool $message
   *   (optional) Set to TRUE to display a status message after rerouting.
   * @param string $mailkeys
   *   (optional) A list of modules or mail keys should be rerouted, defaults to
   *   empty string (all outgoing emails are rerouted).
   */
  public function configureRerouteEmail($enable = NULL, $addresses = NULL, $whitelisted = NULL, $description = NULL, $message = NULL, $mailkeys = NULL) {
    $current_values = $install_values = [
      REROUTE_EMAIL_ENABLE => FALSE,
      REROUTE_EMAIL_ADDRESS => '',
      REROUTE_EMAIL_WHITELIST => '',
      REROUTE_EMAIL_DESCRIPTION => TRUE,
      REROUTE_EMAIL_MESSAGE => TRUE,
      REROUTE_EMAIL_MAILKEYS => '',
    ];

    foreach ($install_values as $key => $value) {
      $current_values[$key] = NULL === $this->rerouteConfig->get($key) ? $value : $this->rerouteConfig->get($key);
    }

    // Configure to Reroute Email settings form.
    $post = [
      REROUTE_EMAIL_ENABLE => NULL === $enable ? $current_values[REROUTE_EMAIL_ENABLE] : $enable,
      REROUTE_EMAIL_ADDRESS => NULL === $addresses ? $current_values[REROUTE_EMAIL_ADDRESS] : $addresses,
      REROUTE_EMAIL_WHITELIST => NULL === $whitelisted ? $current_values[REROUTE_EMAIL_WHITELIST] : $whitelisted,
      REROUTE_EMAIL_DESCRIPTION => NULL === $description ? $current_values[REROUTE_EMAIL_DESCRIPTION] : $description,
      REROUTE_EMAIL_MESSAGE => NULL === $message ? $current_values[REROUTE_EMAIL_MESSAGE] : $message,
      REROUTE_EMAIL_MAILKEYS => NULL === $mailkeys ? $current_values[REROUTE_EMAIL_MAILKEYS] : $mailkeys,
    ];

    // Submit Reroute Email Settings form and check if it was successful.
    $this->drupalPostForm('admin/config/development/reroute_email', $post, t('Save configuration'));
    $this->assertText(t('The configuration options have been saved.'));

    // Rebuild config values after form submit.
    $this->rerouteConfig = $this->config('reroute_email.settings');
  }

  /**
   * Assert whether the text "Originally to: @to_email" is found in email body.
   *
   * @param string $original_destination
   *   (optional) The original email address to be found in rerouted email
   *   body. Defaults to $this->originalDestination if set to NULL.
   */
  public function assertEmailOriginallyTo($original_destination = NULL) {
    // Check most recent email.
    $mails = $this->getMails();
    if (empty($mails)) {
      $this->assert(FALSE, 'Email was not sent.');
      return;
    }

    // Initialize $original_destination by default if no value is provided.
    if (NULL === $original_destination) {
      $original_destination = $this->originalDestination;
    }

    // Search in $mailbody for "Originally to: $original_destination".
    $mail_body = end($mails)['body'];
    $search_for = t('Originally to: @to', ['@to' => $original_destination]);
    $has_info = (bool) preg_match("/{$search_for}/", $mail_body);

    // Asserts whether searched text was found.
    $this->assertTrue($has_info, 'Found the correct "Originally to" line in the body.');
    $this->verbose(new FormattableMarkup('Email body was: <pre>@mail_body</pre>', ['@mail_body' => $mail_body]));
  }

}
