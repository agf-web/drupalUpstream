<?php

namespace Drupal\Tests\link_field_autocomplete_filter\Functional;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\field\Entity\FieldConfig;
use Drupal\node\Entity\Node;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests the autocomplete filter on the link field.
 *
 * @group link_field_autocomplete_filter
 */
class AutocompleteFilterTest extends BrowserTestBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stable';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
    'link',
    'field_ui',
    'link_field_autocomplete_filter',
  ];

  /**
   * A user that can edit content types.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->drupalCreateContentType(['type' => 'first', 'name' => 'First']);
    $this->drupalCreateContentType(['type' => 'second', 'name' => 'Second']);
    $this->drupalCreateContentType(['type' => 'third', 'name' => 'Third']);
    $this->adminUser = $this->drupalCreateUser([], NULL, TRUE);
  }

  /**
   * Tests the validation of the autocomplete filter on the link field.
   */
  public function testAutocompleteFilterUi() {
    $this->drupalLogin($this->adminUser);
    $type_path = 'admin/structure/types/manage/first/fields/add-field';
    $this->drupalGet($type_path);

    $initial_edit = [
      'new_storage_type' => 'link',
      'label' => 'Link field',
      'field_name' => 'link_field',
    ];

    $this->drupalPostForm($type_path, $initial_edit, $this->t('Save and continue'));
    // Save the default storage values.
    $this->getSession()->getPage()->pressButton('Save field settings');

    $this->assertSession()->pageTextContains('Autocomplete Filter');
    $this->assertSession()->checkboxNotChecked('First');
    $this->assertSession()->checkboxNotChecked('Second');
    $this->assertSession()->checkboxNotChecked('Third');

    // Configure the filter.
    $edit = [
      'third_party_settings[link_field_autocomplete_filter][negate]' => 0,
      'third_party_settings[link_field_autocomplete_filter][allowed_content_types][second]' => TRUE,
    ];
    $this->drupalPostForm(NULL, $edit, 'Save settings');

    // Reload the form and assert the values are correctly pre-filled.
    $this->drupalGet('admin/structure/types/manage/first/fields/node.first.field_link_field');
    $this->assertSession()->fieldValueEquals('third_party_settings[link_field_autocomplete_filter][negate]', 0);
    $this->assertSession()->checkboxChecked('third_party_settings[link_field_autocomplete_filter][allowed_content_types][second]');
    $this->assertSession()->checkboxNotChecked('third_party_settings[link_field_autocomplete_filter][allowed_content_types][first]');
    $this->assertSession()->checkboxNotChecked('third_party_settings[link_field_autocomplete_filter][allowed_content_types][third]');

    $field_config = FieldConfig::load('node.first.field_link_field');
    $third_party = $field_config->getThirdPartySettings('link_field_autocomplete_filter');
    $this->assertEquals([
      'negate' => FALSE,
      'allowed_content_types' => [
        'second' => 'second',
        'first' => 0,
        'third' => 0,
      ],
    ], $third_party);

    // Create a node of each type.
    $nodes = [];
    foreach (['first', 'second', 'third'] as $type) {
      $node = Node::create([
        'type' => $type,
        'title' => 'Node of type ' . $type,
      ]);
      $node->save();
      $nodes[$type] = $node;
    }

    // Assert that only the correct nodes can be referenced.
    $cases = [
      'first' => FALSE,
      'second' => TRUE,
      'third' => FALSE,
    ];

    foreach ($cases as $node_type => $valid) {
      $this->drupalGet($nodes['first']->toUrl('edit-form'));
      $this->getSession()->getPage()->fillField('URL', sprintf('Node of type %s (%s)', $node_type, $nodes[$node_type]->id()));
      $this->getSession()->getPage()->pressButton('Save');
      if (!$valid) {
        $this->assertSession()->pageTextContains(sprintf('This node: %s (type %s) cannot be referenced.', $nodes[$node_type]->id(), $node_type));
        continue;
      }
      $this->assertSession()->pageTextContains('Node of type first has been updated.');
    }

    // Negate the filter condition.
    $edit = [
      'third_party_settings[link_field_autocomplete_filter][negate]' => 1,
      'third_party_settings[link_field_autocomplete_filter][allowed_content_types][second]' => TRUE,
    ];
    $this->drupalPostForm('admin/structure/types/manage/first/fields/node.first.field_link_field', $edit, 'Save settings');

    // Reload the form and assert the values are correctly pre-filled.
    $this->drupalGet('admin/structure/types/manage/first/fields/node.first.field_link_field');
    $this->assertSession()->fieldValueEquals('third_party_settings[link_field_autocomplete_filter][negate]', 1);
    $this->assertSession()->checkboxChecked('third_party_settings[link_field_autocomplete_filter][allowed_content_types][second]');
    $this->assertSession()->checkboxNotChecked('third_party_settings[link_field_autocomplete_filter][allowed_content_types][first]');
    $this->assertSession()->checkboxNotChecked('third_party_settings[link_field_autocomplete_filter][allowed_content_types][third]');

    // Run the assertions again, expecting the inverse.
    $cases = [
      'first' => TRUE,
      'second' => FALSE,
      'third' => TRUE,
    ];

    foreach ($cases as $node_type => $valid) {
      $this->drupalGet($nodes['first']->toUrl('edit-form'));
      $this->getSession()->getPage()->fillField('URL', sprintf('Node of type %s (%s)', $node_type, $nodes[$node_type]->id()));
      $this->getSession()->getPage()->pressButton('Save');
      if (!$valid) {
        $this->assertSession()->pageTextContains(sprintf('This node: %s (type %s) cannot be referenced.', $nodes[$node_type]->id(), $node_type));
        continue;
      }
      $this->assertSession()->pageTextContains('Node of type first has been updated.');
    }
  }

}
