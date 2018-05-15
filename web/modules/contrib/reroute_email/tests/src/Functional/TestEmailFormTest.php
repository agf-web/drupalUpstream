<?php

namespace Drupal\Tests\reroute_email\Functional;

use Drupal\Component\Render\FormattableMarkup;

/**
 * Test Reroute Email's form for sending a test email.
 *
 * @ingroup reroute_email_tests
 *
 * @group reroute_email
 */
class TestEmailFormTest extends RerouteEmailTestBase {

  /**
   * Basic tests for reroute_email Test Email form.
   *
   * Check if submitted form values are properly submitted and rerouted.
   * Test Subject, To, Cc, Bcc and Body submitted values, form validation,
   * default values, and submission with invalid email addresses.
   */
  public function testFormTestEmail() {

    // Configure to reroute to {$this->rerouteDestination}.
    $this->configureRerouteEmail(TRUE, $this->rerouteDestination);

    // Check Subject field default value.
    $this->drupalGet('admin/config/development/reroute_email/test');
    $this->assertFieldByName('subject', t('Reroute Email Test'), 'The expected default value was found for the Subject field.');

    // Submit the Test Email form to send an email to be rerouted.
    $post = [
      'to' => 'to@example.com',
      'cc' => 'cc@example.com',
      'bcc' => 'bcc@example.com',
      'subject' => 'Test Reroute Email Test Email Form',
      'body' => 'Testing email rerouting and the Test Email form',
    ];
    $this->drupalPostForm('admin/config/development/reroute_email/test', $post, t('Send email'));
    $this->assertText(t('Test email submitted for delivery from test form.'));
    $mails = $this->getMails();
    $mail = end($mails);

    // Check rerouted email.
    $this->assertMail('to', $this->rerouteDestination, new FormattableMarkup('To email address was rerouted to @address.', ['@address' => $this->rerouteDestination]));
    $this->assertEmailOriginallyTo($post['to']);

    // Check the Cc and Bcc headers are the ones submitted through the form.
    $this->assertTrue($mail['headers']['X-Rerouted-Original-Cc'] == $post['cc'], new FormattableMarkup('X-Rerouted-Original-Cc is correctly set to submitted value: @address', ['@address' => $post['cc']]));
    $this->assertTrue($mail['headers']['X-Rerouted-Original-Bcc'] == $post['bcc'], new FormattableMarkup('X-Rerouted-Original-Cc is correctly set to submitted value: @address', ['@address' => $post['bcc']]));

    // Check that Cc and Bcc headers were added to the message body.
    $copy_headers = [
      'cc' => t('Originally cc: @cc', ['@cc' => $mail['headers']['X-Rerouted-Original-Cc']]),
      'bcc' => t('Originally bcc: @bcc', ['@bcc' => $mail['headers']['X-Rerouted-Original-Bcc']]),
    ];
    foreach ($copy_headers as $header => $message_line) {
      $has_header = preg_match("/{$message_line}/", $mail['body']);
      $this->assertTrue($has_header, new FormattableMarkup('Found the correct "@header" line in the body.', ['@header' => $header]));
    }

    // Check the Subject and Body field values can be found in rerouted email.
    $this->assertMail('subject', $post['subject'], new FormattableMarkup('Subject is correctly set to submitted value: @subject', ['@subject' => $post['subject']]));
    $this->assertFalse(strpos($mail['body'], $post['body']) === FALSE, 'Body contains the value submitted through the form.');

    // Test form submission with email rerouting and invalid email addresses.
    $post = [
      'to' => 'To address invalid format',
      'cc' => 'Cc address invalid format',
      'bcc' => 'Bcc address invalid format',
    ];
    $this->drupalPostForm('admin/config/development/reroute_email/test', $post, t('Send email'));

    // Successful submission with email rerouting enabled.
    $this->assertText(t('Test email submitted for delivery from test form.'));

    // Check rerouted email to.
    $this->assertMail('to', $this->rerouteDestination, new FormattableMarkup('To email address was rerouted to @address.', ['@address' => $this->rerouteDestination]));
    $this->assertEmailOriginallyTo($post['to']);

    // Check the Cc and Bcc headers are the ones submitted through the form.
    $mails = $this->getMails();
    $mail = end($mails);
    $this->assertTrue($mail['headers']['X-Rerouted-Original-Cc'] == $post['cc'], new FormattableMarkup('X-Rerouted-Original-Cc is correctly set to submitted value: @address', ['@address' => $post['cc']]));
    $this->assertTrue($mail['headers']['X-Rerouted-Original-Bcc'] == $post['bcc'], new FormattableMarkup('X-Rerouted-Original-Cc is correctly set to submitted value: @address', ['@address' => $post['bcc']]));

    // Now change the configuration to disable reroute and submit the Test form
    // with the same invalid email address values.
    $this->configureRerouteEmail(FALSE);

    // Submit the test email form again with previously used invalid addresses.
    $this->drupalPostForm('admin/config/development/reroute_email/test', $post, t('Send email'));

    // Check invalid email addresses are still passed to the mail system.
    $mails = $this->getMails();
    $mail = end($mails);

    // Check rerouted email to.
    $this->assertMail('to', $post['to'], new FormattableMarkup('To email address is correctly set to submitted value: @address.', ['@address' => $post['to']]));
    $this->verbose(new FormattableMarkup('Sent email values: <pre>@mail</pre>', ['@mail' => var_export($mail, TRUE)]));

    // Check the Cc and Bcc headers are the ones submitted through the form.
    $this->assertTrue($mail['headers']['Cc'] == $post['cc'], new FormattableMarkup('Cc is correctly set to submitted value: @address', ['@address' => $post['cc']]));
    $this->assertTrue($mail['headers']['Bcc'] == $post['bcc'], new FormattableMarkup('Bcc is correctly set to submitted value: @address', ['@address' => $post['bcc']]));
  }

}
