<?php
namespace WoohooLabsTest\Harmony\Middleware;

use FastRoute\Dispatcher;
use PHPUnit_Framework_TestCase;
use WoohooLabs\Harmony\Dispatcher\CallbackDispatcher;
use WoohooLabs\Harmony\Dispatcher\ClassDispatcher;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware;
use WoohooLabs\Harmony\Middleware\FastRouteMiddleware;
use WoohooLabsTest\Harmony\Utils\Container\DummyContainer;
use WoohooLabsTest\Harmony\Utils\Controller\DummyController;
use WoohooLabsTest\Harmony\Utils\Diactoros\DummyEmitter;
use WoohooLabsTest\Harmony\Utils\FastRoute\DummyDispatcher;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyServerRequest;

class DiactorosResponderMiddlewareTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware::__construct()
     * @covers \WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware::getDispatcher()
     */
    public function testConstruct()
    {
        $middleware = new DiactorosResponderMiddleware(new DummyEmitter());

        $this->assertInstanceOf(DummyEmitter::class, $middleware->getEmitter());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware::getDispatcher()
     * @covers \WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware::setDispatcher()
     */
    public function testSetDispatcher()
    {
        $middleware = new DiactorosResponderMiddleware(null);
        $middleware->setEmitter(new DummyEmitter());

        $this->assertInstanceOf(DummyEmitter::class, $middleware->getEmitter());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware::getId()
     */
    public function testGetId()
    {
        $middleware = new DiactorosResponderMiddleware(null);

        $this->assertEquals("diactoros_responder", $middleware->getId());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware::execute()
     * @expectedException \WoohooLabsTest\Harmony\Utils\Exception\TestException
     */
    public function testExecute()
    {
        $middleware = new DiactorosResponderMiddleware(new DummyEmitter());
        $middleware->execute($this->createHarmony());
    }

    /**
     * @return \WoohooLabs\Harmony\Harmony
     */
    private function createHarmony()
    {
        $harmony = new Harmony();
        $harmony->setContainer(new DummyContainer());
        $harmony->setResponse(new DummyResponse());

        return $harmony;
    }
}
