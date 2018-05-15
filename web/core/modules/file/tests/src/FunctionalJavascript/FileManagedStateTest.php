<?php

namespace Drupal\Tests\file\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\JavascriptTestBase;

/**
 * Tests visibility of managed files.
 *
 * @group file
 */
class FileManagedStateTest extends JavascriptTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['file_test_states', 'file'];

  /**
   * Tests if managed file is correctly hidden with states.
   */
  public function testFileStateVisible() {
    $this->drupalLogin($this->drupalCreateUser());
    $this->drupalGet('file-test-states-form');

    // Wait until the page has fully loaded including the ajax load of the
    // managed files.
    $this->assertSession()->assertWaitOnAjaxRequest();
    $page = $this->getSession()->getPage();

    // Now get the fields from the page.
    $field_initially_visible = $page->findField('files[managed_file_initially_visible]');
    $field_initially_hidden = $page->findField('files[managed_file_initially_hidden]');
    $field_initially_optional = $page->findField('files[managed_file_initially_optional]');

    // Check that the initially visible managed file is visible.
    $this->assertTrue($field_initially_visible->isVisible());

    // Check that the initially hidden managed file is hidden.
    $this->assertFalse($field_initially_hidden->isVisible());

    // Check that the initially optional managed file is optional.
    $this->assertFalse($field_initially_optional->hasAttribute('required'));

    // Toggle the fields.
    $page->findField('toggle')->click();

    // Check that the initially visible managed file is now hidden.
    $this->assertFalse($field_initially_visible->isVisible());

    // Check that the initially hidden managed file is now visible.
    $this->assertTrue($field_initially_hidden->isVisible());

    // Check that the initially optional managed file is now required.
    $this->assertTrue($field_initially_optional->hasAttribute('required'));
  }

}
