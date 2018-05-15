<?php

namespace Drupal\Tests\reroute_email\Functional;

use Drupal\Component\Render\FormattableMarkup;

/**
 * Test Reroute Email with mail keys filter.
 *
 * @ingroup reroute_email_tests
 *
 * @group reroute_email
 */
class MailKeysTest extends RerouteEmailTestBase {

  /**
   * Test Reroute Email with mail keys filter.
   */
  public function testMailKeysFilter() {
    // Configure to reroute all outgoing emails.
    $this->configureRerouteEmail(TRUE, $this->rerouteDestination);

    // Submit a test email (should be rerouted).
    $this->drupalPostForm('admin/config/development/reroute_email/test', ['to' => $this->originalDestination], t('Send email'));

    // Check if the email was rerouted properly.
    $this->assertEmailOriginallyTo();
    $this->assertMail('to', $this->rerouteDestination, new FormattableMarkup('Email was properly rerouted to the email address: @destination.', ['@destination' => $this->rerouteDestination]));

    // Configure to reroute outgoing emails only from our test module.
    $this->configureRerouteEmail(NULL, NULL, NULL, NULL, NULL, 'not_existed_module');

    // Submit a test email (should not be rerouted).
    $this->drupalPostForm('admin/config/development/reroute_email/test', ['to' => $this->originalDestination], t('Send email'));

    // Check if the email was not rerouted.
    $this->assertMail('to', $this->originalDestination, new FormattableMarkup('Email was properly sent the email addresses: @destination.', ['@destination' => $this->originalDestination]));

    // Configure to reroute only outgoing emails from our test form.
    $this->configureRerouteEmail(NULL, NULL, NULL, NULL, NULL, 'reroute_email_test_email_form');

    // Submit a test email (should be rerouted).
    $this->drupalPostForm('admin/config/development/reroute_email/test', ['to' => $this->originalDestination], t('Send email'));

    // Check if the email was rerouted properly.
    $this->assertEmailOriginallyTo();
    $this->assertMail('to', $this->rerouteDestination, new FormattableMarkup('Email was properly rerouted to the email address: @destination.', ['@destination' => $this->rerouteDestination]));

    // Configure to reroute outgoing emails only from our test module.
    $this->configureRerouteEmail(NULL, NULL, NULL, NULL, NULL, 'reroute_email_test');
  }

}
