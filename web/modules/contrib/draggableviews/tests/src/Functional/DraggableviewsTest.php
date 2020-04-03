<?php

namespace Drupal\Tests\draggableviews\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests sortability of Draggableviewws.
 *
 * @group draggableviews
 */
class DraggableviewsTest extends BrowserTestBase {

  use \Drupal\Core\StringTranslation\StringTranslationTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'node',
    'views',
    'draggableviews',
    'draggableviews_demo',
  ];

  /**
   * The installation profile to use with this test.
   *
   * @var string
   */
  protected $profile = 'minimal';

  /**
   * The nodes geenerated at creation.
   *
   * @var array
   */
  protected $generatedNodes = [];

  /**
   * Mock database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    // Create users.
    $this->adminUser = $this->drupalCreateUser([
      'access administration pages',
      'view the administration theme',
      'administer permissions',
      'administer nodes',
      'administer content types',
      'access draggableviews',
    ]);
    $this->authUser = $this->drupalCreateUser([], 'authuser');

    // Gather the test data.
    $dataContent = $this->providerTestDataContent();

    // Create nodes.
    foreach ($dataContent as $datumContent) {
      $node = $this->drupalCreateNode([
        'type' => 'draggableviews_demo',
        'title' => $datumContent[0],
        'promote' => $datumContent[2],
      ]);
      $node->save();

      $this->generatedNodes[] = $node;
    }

    $this->setOrdering();
  }

  /**
   * Data provider for setUp.
   *
   * @return array
   *   Nested array of testing data, Arranged like this:
   *   - Title
   *   - Body
   */
  protected function providerTestDataContent() {
    return [
      [
        'Draggable Content 1',
        'Draggable Content Body 1',
        FALSE,
      ],
      [
        'Draggable Content 2',
        'Draggable Content Body 2',
        FALSE,
      ],
      [
        'Draggable Content 3',
        'Draggable Content Body 3',
        FALSE,
      ],
      [
        'Draggable Content 4',
        'Draggable Content Body 4',
        FALSE,
      ],
      [
        'Draggable Content 5',
        'Draggable Content Body 5',
        FALSE,
      ],
      [
        'Draggable Content 6',
        'Draggable Content Body 6',
        TRUE,
      ],
      [
        'Draggable Content 7',
        'Draggable Content Body 7',
        TRUE,
      ],
      [
        'Draggable Content 8',
        'Draggable Content Body 8',
        TRUE,
      ],
      [
        'Draggable Content 9',
        'Draggable Content Body 9',
        TRUE,
      ],
      [
        'Draggable Content 10',
        'Draggable Content Body 10',
        TRUE,
      ],
    ];
  }

  /**
   * A simple test.
   */
  public function testDraggableviewsContent() {
    $assert_session = $this->assertSession();

    $this->drupalGet('draggableviews-demo/simple');
    $this->assertSession()->statusCodeEquals(200);
    // Verify that anonymous useres cannot access the order page.
    $this->drupalGet('draggableviews-demo/simple/order');
    $this->assertSession()->statusCodeEquals(403);

    // Verify that authorized user has access to display page.
    $this->drupalLogin($this->adminUser);
    $this->drupalGet('draggableviews-demo/simple');
    $this->assertSession()->statusCodeEquals(200);

    // Verify that the page contains generated content.
    $assert_session->pageTextContains(t('Draggable Content 4'));

    // Verify that authorized user has access to order page.
    $this->drupalGet('draggableviews-demo/simple/order');
    $this->assertSession()->statusCodeEquals(200);

    // Verify that the page contains generated content.
    $assert_session->pageTextContains(t('Draggable Content 5'));
  }

  /**
   * Test Draggable views from a visual perspective.
   */
  public function testDraggableviewsComplexVisual() {
    $this->assertSession();
    $this->drupalLogin($this->adminUser);

    // Promoted.
    $this->drupalGet('draggableviews-demo-complex/sort-featured');
    $this->assertSession()->statusCodeEquals(200);

    $this->assertSession()->pageTextContains("Draggable Content 10");
    $this->assertSession()->pageTextNotContains("Draggable Content 2");

    $this->assertSession()->hiddenFieldValueEquals(
      'draggableviews[0][id]',
      $this->generatedNodes[9]->id()
    );

    // Normal.
    $this->drupalGet('draggableviews-demo-complex/sort-standard');
    $this->assertSession()->statusCodeEquals(200);

    $this->assertSession()->pageTextContains("Draggable Content 3");
    $this->assertSession()->pageTextNotContains("Draggable Content 7");

    $this->assertSession()->hiddenFieldValueEquals(
      'draggableviews[0][id]',
      $this->generatedNodes[0]->id()
    );

    $this->drupalGet('draggableviews-demo-complex');
    $this->assertSession()->pageTextMatches('/(Draggable Content 7).*(Draggable Content 6).*(Draggable Content 1).*(Draggable Content 2)/');

    // Check Before Ordering.
    $this->drupalGet('draggableviews-demo-complex/null-before');
    $this->assertSession()->pageTextMatches('/(Draggable Content 2).*(Draggable Content 10).*(Draggable Content 9).*(Draggable Content 8)/');

    // Check After Ordering.
    $this->drupalGet('draggableviews-demo-complex/null-after');
    $this->assertSession()->pageTextMatches('/(Draggable Content 10).*(Draggable Content 9).*(Draggable Content 8).*(Draggable Content 2)/');
  }

  /**
   * Test Draggable views from a data perspective.
   */
  public function testDraggableviewsComplexData() {
    $this->assertSession();
    $this->drupalLogin($this->adminUser);

    // Promoted.
    $view_results = views_get_view_result('draggableviews_demo_complex', 'draggableviews_demo_order_featured');
    $this->assertEquals(5, count($view_results), "We are expecting 5 items in this view. Got: " . count($view_results));

    for ($i = 0; $i <= 4; $i++) {
      $view_nid = $view_results[$i]->_entity->id();
      $expected_nid = $this->generatedNodes[(9 - $i)]->id();
      $this->assertEquals($expected_nid, $view_nid, "Expected $expected_nid and got: $view_nid");
    }

    // Standard.
    $view_results = views_get_view_result('draggableviews_demo_complex', 'draggableviews_demo_order_standard');
    $this->assertEquals(5, count($view_results), "We are expecting 5 items in this view. Got: " . count($view_results));

    for ($i = 0; $i <= 4; $i++) {
      $view_nid = $view_results[$i]->_entity->id();
      $expected_nid = $this->generatedNodes[$i]->id();
      $this->assertEquals($expected_nid, $view_nid, "Expected $expected_nid and got: $view_nid");
    }

    // Check Before Ordering.
    $view_results = views_get_view_result('draggableviews_demo_null_order_test', 'display_null_before');
    $this->assertEquals(10, count($view_results), "We are expecting 5 items in this view. Got: " . count($view_results));

    for ($i = 0; $i <= 9; $i++) {
      $view_nid = $view_results[$i]->_entity->id();
      if ($i >= 5) {
        $expected_nid = $this->generatedNodes[(9 - ($i - 5))]->id();
      }
      else {
        $expected_nid = $this->generatedNodes[$i]->id();
      }

      $this->assertEquals($expected_nid, $view_nid, "Bi: $i :: Expected $expected_nid and got: $view_nid");
    }

    // Check After Ordering.
    $view_results = views_get_view_result('draggableviews_demo_null_order_test', 'display_null_after');
    $this->assertEquals(10, count($view_results), "We are expecting 5 items in this view. Got: " . count($view_results));

    for ($i = 0; $i <= 9; $i++) {
      $view_nid = $view_results[$i]->_entity->id();
      if ($i >= 5) {
        $expected_nid = $this->generatedNodes[($i - 5)]->id();
      }
      else {
        $expected_nid = $this->generatedNodes[(9 - $i)]->id();
      }

      $this->assertEquals($expected_nid, $view_nid, "Ai: $i :: Expected $expected_nid and got: $view_nid");
    }
  }

  /**
   * Set draggableviews ordering.
   */
  protected function setOrdering() {
    // Set weight values.
    $this->rebuildContainer();
    $connection = $this->container->get('database');

    // Promoted nodes.
    $weight = 0;
    for ($i = 9; $i >= 5; $i--) {
      $record = [
        'view_name' => 'draggableviews_demo_complex',
        'view_display' => 'draggableviews_demo_order_featured',
        'args' => '[]',
        'entity_id' => $this->generatedNodes[$i]->id(),
        'weight' => $weight,
        'parent' => 0,
      ];

      $connection->insert('draggableviews_structure')->fields($record)->execute();
      $weight++;
    }

    // Normal nodes.
    $weight = 0;
    for ($i = 0; $i <= 4; $i++) {
      $record = [
        'view_name' => 'draggableviews_demo_complex',
        'view_display' => 'draggableviews_demo_order_standard',
        'args' => '[]',
        'entity_id' => $this->generatedNodes[$i]->id(),
        'weight' => $weight,
        'parent' => 0,
      ];

      $connection->insert('draggableviews_structure')->fields($record)->execute();
      $weight++;
    }

    // Full List.
    $weight = 0;
    for ($i = 0; $i <= 9; $i++) {
      $record = [
        'view_name' => 'draggableviews_demo',
        'view_display' => 'draggableviews_demo_order',
        'args' => '[]',
        'entity_id' => $this->generatedNodes[$i]->id(),
        'weight' => $weight,
        'parent' => 0,
      ];

      $connection->insert('draggableviews_structure')->fields($record)->execute();
      $weight++;
    }

    // Full list reversed.
    $weight = 0;
    for ($i = 9; $i >= 0; $i--) {
      $record = [
        'view_name' => 'draggableviews_demo',
        'view_display' => 'draggableviews_demo_order_reversed',
        'args' => '[]',
        'entity_id' => $this->generatedNodes[$i]->id(),
        'weight' => $weight,
        'parent' => 0,
      ];

      $connection->insert('draggableviews_structure')->fields($record)->execute();
      $weight++;
    }
  }

}
