<?php
namespace Drupal\Tests\autocomplete_redirect\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\autocomplete_redirect\ExampleService;

/**
 * Tests for the ExampleService.
 *
 * @group example_module
 */
class ExampleServiceTest extends UnitTestCase {

  /**
   * Test the add() method of ExampleService.
   */
  public function testAdd() {
    // Create an instance of the service.
    $service = new ExampleService();

    // Assert that adding 2 and 3 gives 5.
    $this->assertEquals(5, $service->add(2, 3), 'Adding 2 + 3 should return 5.');

    // Assert that adding -1 and 1 gives 0.
    $this->assertEquals(0, $service->add(-1, 1), 'Adding -1 + 1 should return 0.');

    // Assert that adding -1 and -2 gives -3.
    $this->assertEquals(-3, $service->add(-1, -2), 'Adding -1 + -2 should return -3.');
  }
}
