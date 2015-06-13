<?php
namespace WoohooLabsTest\Harmony\Container;

use WoohooLabs\Harmony\Container\BasicContainer;
use WoohooLabsTest\Harmony\Dispatcher\DummyController;

class BasicContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \WoohooLabs\Harmony\Container\BasicContainer::has()
     * @covers \WoohooLabs\Harmony\Container\BasicContainer::get()
     */
    public function testController()
    {
        $container = new BasicContainer();

        $this->assertEquals(true, $container->has(DummyController::class));
        $this->assertInstanceOf(DummyController::class, $container->get(DummyController::class));
    }

    /**
     * @covers \WoohooLabs\Harmony\Container\BasicContainer::has()
     */
    public function testHasNotExistingClass()
    {
        $container = new BasicContainer();

        $this->assertEquals(false, $container->has("FooBarClass"));
    }

    /**
     * @covers \WoohooLabs\Harmony\Container\BasicContainer::get()
     * @expectedException \WoohooLabs\Harmony\Container\ContainerNotFoundException
     */
    public function testGetNotExistingClass()
    {
        $container = new BasicContainer();

        $this->assertEquals(false, $container->get("FooBarClass"));
    }
}
