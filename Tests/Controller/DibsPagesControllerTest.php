<?php

namespace Drupal\dibs\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the dibs module.
 */
class DibsPagesControllerTest extends WebTestBase {
  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => "dibs DibsPagesController's controller functionality",
      'description' => 'Test Unit for module dibs and controller DibsPagesController.',
      'group' => 'Other',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests dibs functionality.
   */
  public function testDibsPagesController() {
    // Check that the basic functions of module dibs.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via App Console.');
  }

}
