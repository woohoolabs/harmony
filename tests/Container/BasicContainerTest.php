<?php
namespace WoohooLabsTest\Harmony\Container;

use WoohooLabs\Harmony\Container\BasicContainer;
use WoohooLabsTest\Harmony\Utils\Controller\DummyController;

class BasicContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function instantiateController()
    {
        $container = new BasicContainer();

        $this->assertEquals(true, $container->has(DummyController::class));
        $this->assertInstanceOf(DummyController::class, $container->get(DummyController::class));
    }

    /**
     * @test
     */
    public function hasNotExistingClass()
    {
        $container = new BasicContainer();

        $this->assertEquals(false, $container->has("FooBarClass"));
    }

    /**
     * @test
     * @expectedException \WoohooLabs\Harmony\Container\BasicContainerNotFoundException
     */
    public function getNotExistingClass()
    {
        $container = new BasicContainer();

        $this->assertEquals(false, $container->get("FooBarClass"));
    }
}
