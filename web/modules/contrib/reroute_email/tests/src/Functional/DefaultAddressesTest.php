<?php

namespace Drupal\Tests\reroute_email\Functional;

use Drupal\Component\Render\FormattableMarkup;

/**
 * Test for default address.
 *
 * When reroute email addresses field is not configured, attempt to use the site
 * email address, otherwise use sendmail_from system variable.
 *
 * @ingroup reroute_email_tests
 *
 * @group reroute_email
 */
class DefaultAddressesTest extends RerouteEmailTestBase {

  public static $modules = ['reroute_email', 'dblog'];

  /**
   * Enable modules and create user with specific permissions.
   */
  public function setUp() {
    // Add more permissions to access recent log messages in test.
    $this->permissions[] = 'access site reports';
    parent::setUp();
  }

  /**
   * Test reroute email address is set to site_mail, sendmail_from or empty.
   *
   * When reroute email addresses field is not configured and settings haven't
   * been configured yet, check if the site email address or the sendmail_from
   * system variable are properly used as fallbacks. Additionally, check that
   * emails are aborted and a watchdog entry logged if reroute email address is
   * set to an empty string.
   */
  public function testRerouteDefaultAddress() {

    // Check default value for reroute_email_address when not configured.
    // If system.site's 'mail' is not empty, it should be the default value.
    $site_mail = $this->config('system.site')->get('mail');
    $this->assertTrue(isset($site_mail), new FormattableMarkup('Site mail is not empty: @site_mail.', ['@site_mail' => $site_mail]));

    // Programmatically enable email rerouting.
    $this->rerouteConfig->set(REROUTE_EMAIL_ENABLE, TRUE)->save();

    // Load the Reroute Email Settings form page. Ensure rerouting is enabled.
    $this->drupalGet('admin/config/development/reroute_email');
    $this->assertFieldChecked('edit-enable', 'Email rerouting was programmatically successfully enabled.');
    $this->assertTrue($this->rerouteConfig->get(REROUTE_EMAIL_ENABLE), 'Rerouting is enabled.');

    // Email addresses field default value is system.site.mail.
    $this->assertFieldByName(REROUTE_EMAIL_ADDRESS, $site_mail, new FormattableMarkup('reroute_email_address default value on form is system.site.mail value: @site_mail.', ['@site_mail' => $site_mail]));

    // Ensure reroute_email_address is actually empty at this point.
    $this->assertNull($this->rerouteConfig->get(REROUTE_EMAIL_ADDRESS), 'Reroute email destination address is not configured.');

    // Submit a test email, check if it is rerouted to system.site.mail address.
    $this->drupalPostForm('admin/config/development/reroute_email/test', ['to' => 'to@example.com'], 'Send email');
    $this->assertText(t('Test email submitted for delivery from test form.'));
    $this->assert(count($this->getMails()) === 1, 'Exactly one email captured.');
    $this->verboseEmail();

    // Check rerouted email is the site email address.
    $this->assertMail('to', $site_mail, new FormattableMarkup('Email was properly rerouted to site email address: @default_destination.', ['@default_destination' => $site_mail]));

    // Unset system.site.mail.
    $this
      ->config('system.site')
      ->set('mail', NULL)
      ->save();

    // Configure whitelisted  addresses as an empty string to about all emails.
    $this->configureRerouteEmail(TRUE, '', '');

    // Make sure configured emails values are an empty string.
    $this->assertTrue($this->rerouteConfig->get(REROUTE_EMAIL_ADDRESS) === '', 'Reroute email destination address is an empty string.');
    $this->assertTrue($this->rerouteConfig->get(REROUTE_EMAIL_WHITELIST) === '', 'Whitelisted email address is an empty string.');

    // Flush the Test Mail collector to ensure it is empty for this tests.
    \Drupal::state()->set('system.test_mail_collector', []);

    // Submit a test email to check if it is aborted.
    $this->drupalPostForm('admin/config/development/reroute_email/test', ['to' => 'to@example.com'], t('Send email'));
    $mails = $this->getMails();
    $mail = end($mails);
    $this->assertTrue(count($mails) == 0, 'Email sending was properly aborted because rerouting email address is an empty string.');

    // Check status message is displayed properly after email form submission.
    $this->assertPattern(t('/@message_id.*was aborted by reroute email/', ['@message_id' => $mail['id']]),
      new FormattableMarkup('Status message displayed as expected to the user with the mail ID <em>(@message_id)</em> and a link to recent log entries.', ['@message_id' => $mail['id']]));

    // Check the watchdog entry logged with aborted email message.
    $this->drupalGet('admin/reports/dblog');

    // Check the link to the watchdog detailed message.
    $dblog_link = $this->xpath('//table[@id="admin-dblog"]/tbody/tr[contains(@class,"dblog-reroute-email")][1]/td[text()="reroute_email"]/following-sibling::td/a[contains(text(),"reroute_email")]');
    $link_label = $dblog_link[0]->getText();
    $this->assertTrue(isset($dblog_link[0]), new FormattableMarkup('Logged a message in dblog: <em>@link</em>.', ['@link' => $link_label]));

    // Open the full view page of the log message found for reroute_email.
    $this->clickLink($link_label);

    // Ensure the correct email is logged with default 'to' placeholder.
    $this->assertPattern(t('/Aborted email sending for.*@message_id.*Detailed email data/', ['@message_id' => $mail['id']]),
      new FormattableMarkup('The dblog entry recorded by Reroute Email contains a dump of the aborted email message <em>@message_id</em> and is formatted as expected.', ['@message_id' => $mail['id']]));
  }

}
