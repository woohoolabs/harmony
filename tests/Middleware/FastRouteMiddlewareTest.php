<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Middleware;

use FastRoute\Dispatcher;
use PHPUnit\Framework\TestCase;
use WoohooLabs\Harmony\Exception\MethodNotAllowed;
use WoohooLabs\Harmony\Exception\RouteNotFound;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\FastRouteMiddleware;
use WoohooLabs\Harmony\Tests\Utils\Controller\DummyController;
use WoohooLabs\Harmony\Tests\Utils\FastRoute\StubDispatcher;
use WoohooLabs\Harmony\Tests\Utils\Psr7\DummyResponse;
use WoohooLabs\Harmony\Tests\Utils\Psr7\DummyServerRequest;

class FastRouteMiddlewareTest extends TestCase
{
    /**
     * @test
     */
    public function construct()
    {
        $middleware = new FastRouteMiddleware(new StubDispatcher());

        $this->assertInstanceOf(StubDispatcher::class, $middleware->getFastRoute());
    }

    /**
     * @test
     */
    public function setFastRoute()
    {
        $middleware = new FastRouteMiddleware(null);
        $middleware->setFastRoute(new StubDispatcher());

        $this->assertInstanceOf(StubDispatcher::class, $middleware->getFastRoute());
    }

    /**
     * @test
     */
    public function invokeRouteNotFound()
    {
        $harmony = $this->createHarmony();
        $route = [Dispatcher::NOT_FOUND];
        $middleware = new FastRouteMiddleware(new StubDispatcher($route));

        $this->expectException(RouteNotFound::class);
        $middleware->process($harmony->getRequest(), $harmony);
    }

    /**
     * @test
     */
    public function invokeMethodNotAllowed()
    {
        $harmony = $this->createHarmony();
        $route = [Dispatcher::METHOD_NOT_ALLOWED];
        $middleware = new FastRouteMiddleware(new StubDispatcher($route));

        $this->expectException(MethodNotAllowed::class);
        $middleware->process($harmony->getRequest(), $harmony);
    }

    /**
     * @test
     */
    public function invoke()
    {
        $harmony = $this->createHarmony();
        $route = [Dispatcher::FOUND, [DummyController::class, "dummyAction"], []];
        $middleware = new FastRouteMiddleware(new StubDispatcher($route));
        $middleware->process($harmony->getRequest(), $harmony);

        $this->assertEquals(
            [DummyController::class, "dummyAction"],
            $harmony->getRequest()->getAttribute("__action")
        );
    }

    /**
     * @test
     */
    public function invokeAttributesPassed()
    {
        $harmony = $this->createHarmony();
        $route = [Dispatcher::FOUND, ["", ""], ["arg1" => "val1", "arg2" => "val2"]];
        $middleware = new FastRouteMiddleware(new StubDispatcher($route));
        $middleware->process($harmony->getRequest(), $harmony);

        $this->assertEquals("val1", $harmony->getRequest()->getAttribute("arg1"));
        $this->assertEquals("val2", $harmony->getRequest()->getAttribute("arg2"));
    }

    private function createHarmony()
    {
        return new Harmony(new DummyServerRequest(), new DummyResponse());
    }
}
