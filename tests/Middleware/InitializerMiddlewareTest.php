<?php
namespace WoohooLabsTest\Harmony\Middleware;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Harmony\Container\BasicContainer;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\InitializerMiddleware;
use WoohooLabsTest\Harmony\Utils\Container\DummyContainer;
use WoohooLabsTest\Harmony\Utils\Middleware\ExceptionMiddleware;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyServerRequest;

class InitializerMiddlewareTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \WoohooLabs\Harmony\Middleware\InitializerMiddleware::getId()
     */
    public function testGetId()
    {
        $middleware = new InitializerMiddleware(new DummyServerRequest(), new DummyResponse());

        $this->assertEquals(InitializerMiddleware::ID, $middleware->getId());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\InitializerMiddleware::__construct()
     * @covers \WoohooLabs\Harmony\Middleware\InitializerMiddleware::execute()
     */
    public function testInitializationWithoutProvidedContainer()
    {
        $harmony = new Harmony();
        $middleware = new InitializerMiddleware(new DummyServerRequest(), new DummyResponse());
        $middleware->execute($harmony);

        $this->assertInstanceOf(DummyServerRequest::class, $harmony->getRequest());
        $this->assertInstanceOf(DummyResponse::class, $harmony->getResponse());
        $this->assertInstanceOf(BasicContainer::class, $harmony->getContainer());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\InitializerMiddleware::__construct()
     * @covers \WoohooLabs\Harmony\Middleware\InitializerMiddleware::execute()
     */
    public function testFullInitialization()
    {
        $harmony = new Harmony();
        $middleware = new InitializerMiddleware(new DummyServerRequest(), new DummyResponse(), new DummyContainer());
        $middleware->execute($harmony);

        $this->assertInstanceOf(DummyServerRequest::class, $harmony->getRequest());
        $this->assertInstanceOf(DummyResponse::class, $harmony->getResponse());
        $this->assertInstanceOf(DummyContainer::class, $harmony->getContainer());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\InitializerMiddleware::execute()
     * @expectedException \WoohooLabsTest\Harmony\Utils\Exception\TestException
     * @expectedExceptionMessage next
     */
    public function testCallingNextMiddleware()
    {
        $harmony = new Harmony();
        $harmony->addMiddleware(new ExceptionMiddleware("next"));

        $middleware = new InitializerMiddleware(new DummyServerRequest(), new DummyResponse(), new DummyContainer());
        $middleware->execute($harmony);
    }
}
