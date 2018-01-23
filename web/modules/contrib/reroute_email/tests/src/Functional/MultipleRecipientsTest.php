<?php

namespace Drupal\Tests\reroute_email\Functional;

use Drupal\Component\Render\FormattableMarkup;

/**
 * Test Reroute Email with multiple recipients.
 *
 * @ingroup reroute_email_tests
 *
 * @group reroute_email
 */
class MultipleRecipientsTest extends RerouteEmailTestBase {

  /**
   * Test Reroute Email with multiple recipients.
   */
  public function testMultipleRecipients() {
    // Set multiple whitelisted domain and rerouting emails. Multiple commas and
    // semicolons are added for validation tests.
    $this->configureRerouteEmail(TRUE, 'user1@example.com, user2@example.com,;;,,user@example.com', $this->whitelistedDomain);

    // Make sure configured emails were set properly.
    $reroute_to = 'user1@example.com,user2@example.com,user@example.com';
    $this->assertEqual($this->rerouteConfig->get(REROUTE_EMAIL_ADDRESS), $reroute_to, 'Reroute email address was set.');
    $this->assertEqual($this->rerouteConfig->get(REROUTE_EMAIL_WHITELIST), $this->whitelistedDomain, 'Whitelisted value was set.');

    // Submit a test email (should be rerouted).
    $to = 'some@not-exist.domain, whitelisted@example.com';
    $this->drupalPostForm('admin/config/development/reroute_email/test', ['to' => $to], t('Send email'));

    // Check if the email was rerouted properly.
    $this->assertEmailOriginallyTo($to);
    $this->assertMail('to', $reroute_to, new FormattableMarkup('Email was properly rerouted to the email address: @destination.', ['@destination' => $reroute_to]));

    // Submit a test email (should not be rerouted).
    $to = 'whitelisted@example.com, user2@example.com, allowed@example.com';
    $this->drupalPostForm('admin/config/development/reroute_email/test', ['to' => $to], t('Send email'));

    // Check if the email was not rerouted.
    $this->assertMail('to', $to, new FormattableMarkup('Email was properly sent the email addresses: @destination.', ['@destination' => $to]));
  }

}
