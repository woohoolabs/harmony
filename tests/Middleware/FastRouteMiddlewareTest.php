<?php
namespace WoohooLabsTest\Harmony\Middleware;

use FastRoute\Dispatcher;
use PHPUnit_Framework_TestCase;
use WoohooLabs\Harmony\Dispatcher\CallbackDispatcher;
use WoohooLabs\Harmony\Dispatcher\ClassDispatcher;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\FastRouteMiddleware;
use WoohooLabsTest\Harmony\Utils\Container\DummyContainer;
use WoohooLabsTest\Harmony\Utils\Controller\DummyController;
use WoohooLabsTest\Harmony\Utils\FastRoute\DummyDispatcher;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyServerRequest;

class FastRouteMiddlewareTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \WoohooLabs\Harmony\Middleware\FastRouteMiddleware::__construct()
     * @covers \WoohooLabs\Harmony\Middleware\FastRouteMiddleware::getDispatcher()
     */
    public function testConstruct()
    {
        $middleware = new FastRouteMiddleware(new DummyDispatcher());

        $this->assertInstanceOf(DummyDispatcher::class, $middleware->getDispatcher());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\FastRouteMiddleware::getDispatcher()
     * @covers \WoohooLabs\Harmony\Middleware\FastRouteMiddleware::setDispatcher()
     */
    public function testSetDispatcher()
    {
        $middleware = new FastRouteMiddleware(null);
        $middleware->setDispatcher(new DummyDispatcher());

        $this->assertInstanceOf(DummyDispatcher::class, $middleware->getDispatcher());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\FastRouteMiddleware::getId()
     */
    public function testGetId()
    {
        $middleware = new FastRouteMiddleware(null);

        $this->assertEquals("fast_route", $middleware->getId());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\FastRouteMiddleware::execute()
     * @expectedException \WoohooLabs\Harmony\Router\RouteNotFoundException
     */
    public function testExecuteRouteNotFound()
    {
        $route = [Dispatcher::NOT_FOUND];
        $middleware = new FastRouteMiddleware(new DummyDispatcher($route));
        $middleware->execute($this->createHarmony());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\FastRouteMiddleware::execute()
     * @expectedException \WoohooLabs\Harmony\Router\MethodNotAllowedException
     */
    public function testExecuteMethodNotAllowed()
    {
        $route = [Dispatcher::METHOD_NOT_ALLOWED];
        $middleware = new FastRouteMiddleware(new DummyDispatcher($route));
        $middleware->execute($this->createHarmony());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\FastRouteMiddleware::execute()
     * @expectedException \WoohooLabs\Harmony\Router\RouteNotFoundException
     */
    public function testExecuteGenericError()
    {
        $route = [""];
        $middleware = new FastRouteMiddleware(new DummyDispatcher($route));
        $middleware->execute($this->createHarmony());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\FastRouteMiddleware::execute()
     */
    public function testExecuteClassDispatcher()
    {
        $route = [Dispatcher::FOUND, [DummyController::class, "dummyAction"], []];
        $middleware = new FastRouteMiddleware(new DummyDispatcher($route));
        $harmony = $this->createHarmony();
        $middleware->execute($harmony);

        /** @var \WoohooLabs\Harmony\Dispatcher\ClassDispatcher $dispatcher */
        $dispatcher = $harmony->getDispatcher();
        $this->assertInstanceOf(ClassDispatcher::class, $dispatcher);
        $this->assertEquals(DummyController::class, $dispatcher->getClassName());
        $this->assertEquals("dummyAction", $dispatcher->getMethodName());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\FastRouteMiddleware::execute()
     */
    public function testExecuteCallbackDispatcher()
    {
        $callback = function () {
        };

        $route = [Dispatcher::FOUND, $callback, []];
        $middleware = new FastRouteMiddleware(new DummyDispatcher($route));
        $harmony = $this->createHarmony();
        $middleware->execute($harmony);

        /** @var \WoohooLabs\Harmony\Dispatcher\CallbackDispatcher $dispatcher */
        $dispatcher = $harmony->getDispatcher();
        $this->assertInstanceOf(CallbackDispatcher::class, $dispatcher);
        $this->assertInternalType("callable", $dispatcher->getCallback());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\FastRouteMiddleware::execute()
     */
    public function testExecuteAttributesPassed()
    {
        $route = [Dispatcher::FOUND, ["", ""], ["arg1" => "val1", "arg2" => "val2"]];
        $middleware = new FastRouteMiddleware(new DummyDispatcher($route));
        $harmony = $this->createHarmony();
        $middleware->execute($harmony);

        $this->assertEquals("val1", $harmony->getRequest()->getAttribute("arg1"));
        $this->assertEquals("val2", $harmony->getRequest()->getAttribute("arg2"));
    }

    /**
     * @return \WoohooLabs\Harmony\Harmony
     */
    private function createHarmony()
    {
        $harmony = new Harmony();
        $harmony->setContainer(new DummyContainer());
        $harmony->setRequest(new DummyServerRequest());

        return $harmony;
    }
}
