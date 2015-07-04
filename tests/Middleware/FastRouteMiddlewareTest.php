<?php
namespace WoohooLabsTest\Harmony\Middleware;

use FastRoute\Dispatcher;
use PHPUnit_Framework_TestCase;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\FastRouteMiddleware;
use WoohooLabsTest\Harmony\Utils\Controller\DummyController;
use WoohooLabsTest\Harmony\Utils\FastRoute\DummyDispatcher;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyServerRequest;

class FastRouteMiddlewareTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \WoohooLabs\Harmony\Middleware\FastRouteMiddleware::__construct()
     * @covers \WoohooLabs\Harmony\Middleware\FastRouteMiddleware::getFastRoute()
     */
    public function testConstruct()
    {
        $middleware = new FastRouteMiddleware(new DummyDispatcher());

        $this->assertInstanceOf(DummyDispatcher::class, $middleware->getFastRoute());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\FastRouteMiddleware::getFastRoute()
     * @covers \WoohooLabs\Harmony\Middleware\FastRouteMiddleware::setFastRoute()
     */
    public function testSetFastRoute()
    {
        $middleware = new FastRouteMiddleware(null);
        $middleware->setFastRoute(new DummyDispatcher());

        $this->assertInstanceOf(DummyDispatcher::class, $middleware->getFastRoute());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\FastRouteMiddleware::__invoke()
     * @expectedException \WoohooLabs\Harmony\Exception\RouteNotFoundException
     */
    public function testInvokeRouteNotFound()
    {
        $harmony = $this->createHarmony();
        $route = [Dispatcher::NOT_FOUND];
        $middleware = new FastRouteMiddleware(new DummyDispatcher($route));
        $middleware($harmony->getRequest(), $harmony->getResponse(), $harmony);
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\FastRouteMiddleware::__invoke()
     * @expectedException \WoohooLabs\Harmony\Exception\MethodNotAllowedException
     */
    public function testInvokeMethodNotAllowed()
    {
        $harmony = $this->createHarmony();
        $route = [Dispatcher::METHOD_NOT_ALLOWED];
        $middleware = new FastRouteMiddleware(new DummyDispatcher($route));
        $middleware($harmony->getRequest(), $harmony->getResponse(), $harmony);
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\FastRouteMiddleware::__invoke()
     * @expectedException \WoohooLabs\Harmony\Exception\RouteNotFoundException
     */
    public function testInvokeGenericError()
    {
        $harmony = $this->createHarmony();
        $route = [""];
        $middleware = new FastRouteMiddleware(new DummyDispatcher($route));
        $middleware($harmony->getRequest(), $harmony->getResponse(), $harmony);
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\FastRouteMiddleware::__invoke()
     */
    public function testInvoke()
    {
        $harmony = $this->createHarmony();
        $route = [Dispatcher::FOUND, [DummyController::class, "dummyAction"], []];
        $middleware = new FastRouteMiddleware(new DummyDispatcher($route));
        $middleware($harmony->getRequest(), $harmony->getResponse(), $harmony);

        $this->assertEquals([DummyController::class, "dummyAction"], $harmony->getRequest()->getAttribute("__callable"));
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\FastRouteMiddleware::__invoke()
     */
    public function testInvokeAttributesPassed()
    {
        $harmony = $this->createHarmony();
        $route = [Dispatcher::FOUND, ["", ""], ["arg1" => "val1", "arg2" => "val2"]];
        $middleware = new FastRouteMiddleware(new DummyDispatcher($route));
        $middleware($harmony->getRequest(), $harmony->getResponse(), $harmony);

        $this->assertEquals("val1", $harmony->getRequest()->getAttribute("arg1"));
        $this->assertEquals("val2", $harmony->getRequest()->getAttribute("arg2"));
    }

    /**
     * @return \WoohooLabs\Harmony\Harmony
     */
    private function createHarmony()
    {
        return new Harmony(new DummyServerRequest(), new DummyResponse());
    }
}
