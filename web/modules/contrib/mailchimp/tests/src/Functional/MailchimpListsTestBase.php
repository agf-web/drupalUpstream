<?php

namespace Drupal\Tests\mailchimp_lists\Functional;

use Drupal\mailchimp_lists_test\MailchimpListsConfigOverrider;
use Drupal\Tests\BrowserTestBase;

include_once __DIR__ . "/../../../../../lib/mailchimp-api-php/tests/src/Client.php";
include_once __DIR__ . "/../../../../../lib/mailchimp-api-php/tests/src/Mailchimp.php";
include_once __DIR__ . "/../../../../../lib/mailchimp-api-php/tests/src/MailchimpTestHttpResponse.php";
include_once __DIR__ . "/../../../../../lib/mailchimp-api-php/tests/src/MailchimpTestHttpClient.php";
include_once __DIR__ . "/../../../../../lib/mailchimp-api-php/tests/src/MailchimpLists.php";

/**
 * Sets up Mailchimp Lists/Audiences module tests.
 */
abstract class MailchimpListsTestBase extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'classy';

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    \Drupal::configFactory()->addOverride(new MailchimpListsConfigOverrider());
  }

}
