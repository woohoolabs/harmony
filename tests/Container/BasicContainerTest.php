<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Container;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Harmony\Container\BasicContainer;
use WoohooLabs\Harmony\Container\BasicContainerNotFoundException;
use WoohooLabs\Harmony\Tests\Utils\Controller\DummyController;

class BasicContainerTest extends TestCase
{
    /**
     * @test
     */
    public function instantiateController()
    {
        $container = new BasicContainer();

        $hasController = $container->has(DummyController::class);
        $getController = $container->get(DummyController::class);

        $this->assertTrue($hasController);
        $this->assertInstanceOf(DummyController::class, $getController);
    }

    /**
     * @test
     */
    public function hasNotExistingClass()
    {
        $container = new BasicContainer();

        $hasItem = $container->has("FooBarClass");

        $this->assertFalse($hasItem);
    }

    /**
     * @test
     */
    public function getNotExistingClass()
    {
        $container = new BasicContainer();

        $this->expectException(BasicContainerNotFoundException::class);

        $this->assertFalse($container->get("FooBarClass"));
    }
}
