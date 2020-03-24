<?php

namespace Drupal\KernelTests\Core\Common;

use Drupal\Core\Form\FormHelper;
use Drupal\KernelTests\KernelTestBase;

/**
 * @covers \Drupal\Core\Form\FormHelper::processStates
 * @group Common
 */
class DrupalProcessStatesTest extends KernelTestBase {

  /**
   * Tests that FormHelper::processStates() doesn't cause any notices.
   */
  public function testProcessStates() {
    // Create a form element without specifying a '#type'.
    $form_element = [
      '#markup' => 'Custom markup',
      '#states' => [
        'visible' => [
          ':select[name="method"]' => ['value' => 'email'],
        ],
      ],
    ];
    FormHelper::processStates($form_element);
    $this->assertArrayHasKey('#attributes', $form_element);
  }

}
