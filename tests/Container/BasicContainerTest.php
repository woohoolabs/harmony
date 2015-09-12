<?php
namespace WoohooLabsTest\Harmony\Container;

use WoohooLabs\Harmony\Container\BasicContainer;
use WoohooLabsTest\Harmony\Utils\Controller\DummyController;

class BasicContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testController()
    {
        $container = new BasicContainer();

        $this->assertEquals(true, $container->has(DummyController::class));
        $this->assertInstanceOf(DummyController::class, $container->get(DummyController::class));
    }

    public function testHasNotExistingClass()
    {
        $container = new BasicContainer();

        $this->assertEquals(false, $container->has("FooBarClass"));
    }

    /**
     * @expectedException \WoohooLabs\Harmony\Container\ContainerNotFoundException
     */
    public function testGetNotExistingClass()
    {
        $container = new BasicContainer();

        $this->assertEquals(false, $container->get("FooBarClass"));
    }
}
