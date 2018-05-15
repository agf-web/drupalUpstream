<?php

namespace Drupal\paragraphs\Tests\Experimental;

/**
 * Tests paragraphs experimental alter widget by type.
 *
 * @group paragraphs
 */
class ParagraphsExperimentalAlterByTypeTest extends ParagraphsExperimentalTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'paragraphs_test',
  ];


  /**
   * Test widget alter based on paragraph type
   */
  public function testAlterBasedOnType() {
    $this->addParagraphedContentType('paragraphed_test', 'field_paragraphs', 'entity_reference_paragraphs');

    $this->loginAsAdmin(['create paragraphed_test content', 'edit any paragraphed_test content']);
    // Add a Paragraph type.
    $paragraph_type = 'altered_paragraph';
    $this->addParagraphsType($paragraph_type);

    // Add a text field to the altered_paragraph type.
    static::fieldUIAddNewField('admin/structure/paragraphs_type/' . $paragraph_type, 'text', 'Normal title', 'text_long', [], []);

    // Test the hook_field_widget_WIDGET_TYPE_form_alter hook. Which should alter the title of the text_field of our paragraph.
    $this->drupalGet('node/add/paragraphed_test');
    $this->assertText('Altered title');
  }
}