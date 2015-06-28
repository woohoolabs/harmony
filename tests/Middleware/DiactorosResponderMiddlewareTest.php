<?php
namespace WoohooLabsTest\Harmony\Middleware;

use FastRoute\Dispatcher;
use PHPUnit_Framework_TestCase;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware;
use WoohooLabsTest\Harmony\Utils\Container\DummyContainer;
use WoohooLabsTest\Harmony\Utils\Diactoros\DummyEmitter;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;

class DiactorosResponderMiddlewareTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware::__construct()
     * @covers \WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware::getEmitter()
     */
    public function testConstruct()
    {
        $middleware = new DiactorosResponderMiddleware(new DummyEmitter());

        $this->assertInstanceOf(DummyEmitter::class, $middleware->getEmitter());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware::getEmitter()
     * @covers \WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware::setEmitter()
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
     */
    public function testExecute()
    {
        $middleware = new DiactorosResponderMiddleware(new DummyEmitter());

        $this->assertEquals(true, $middleware->execute($this->createHarmony()));
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
