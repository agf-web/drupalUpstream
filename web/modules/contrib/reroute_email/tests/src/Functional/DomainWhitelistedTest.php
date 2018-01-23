<?php

namespace Drupal\Tests\reroute_email\Functional;

use Drupal\Component\Render\FormattableMarkup;

/**
 * Test Reroute Email's with a domain whitelisted.
 *
 * @ingroup reroute_email_tests
 *
 * @group reroute_email
 */
class DomainWhitelistedTest extends RerouteEmailTestBase {

  /**
   * Basic tests for the domain whitelisted addresses.
   */
  public function testDomainWhitelistedEmail() {
    // Set rerouting email and whitelisted domain.
    $this->configureRerouteEmail(TRUE, $this->rerouteDestination, $this->whitelistedDomain);

    // Make sure configured emails were set.
    $this->assertEqual($this->rerouteConfig->get(REROUTE_EMAIL_ADDRESS), $this->rerouteDestination, 'Reroute email address was set.');
    $this->assertEqual($this->rerouteConfig->get(REROUTE_EMAIL_WHITELIST), $this->whitelistedDomain, 'Whitelisted value was set.');

    // Submit a test email (should be rerouted).
    $to = 'some@not-exist.domain';
    $this->drupalPostForm('admin/config/development/reroute_email/test', ['to' => $to], t('Send email'));

    // Check if the email was rerouted properly.
    $this->assertEmailOriginallyTo($to);
    $this->assertMail('to', $this->rerouteDestination, new FormattableMarkup('Email was properly rerouted to the email address: @destination.', ['@destination' => $this->rerouteDestination]));

    // Submit a test email (should not be rerouted).
    $this->drupalPostForm('admin/config/development/reroute_email/test', ['to' => $this->originalDestination], t('Send email'));

    // Check if the email was rerouted properly.
    $this->assertMail('to', $this->originalDestination, new FormattableMarkup('Email was properly sent the email address: @destination.', ['@destination' => $this->originalDestination]));
  }

}
