<?php
namespace WoohooLabsTest\Harmony;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Harmony\Container\BasicContainer;
use WoohooLabs\Harmony\Dispatcher\CallbackDispatcher;
use WoohooLabs\Harmony\Harmony;
use WoohooLabsTest\Harmony\Utils\Middleware\DummyMiddleware;
use WoohooLabsTest\Harmony\Utils\Middleware\ExceptionMiddleware;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyServerRequest;

class HarmonyTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \WoohooLabs\Harmony\Harmony::build()
     */
    public function testBuild()
    {
        $harmony = Harmony::build();

        $this->assertInstanceOf(Harmony::class, $harmony);
    }

    /**
     * @covers \WoohooLabs\Harmony\Harmony::live()
     * @covers \WoohooLabs\Harmony\Harmony::addMiddleware()
     * @expectedException \WoohooLabsTest\Harmony\Utils\Exception\TestException
     * @expectedExceptionMessage dummy1
     */
    public function testLive()
    {
        $harmony = new Harmony();
        $harmony->addMiddleware(new ExceptionMiddleware("dummy1"));
        $harmony->live();
    }

    /**
     * @covers \WoohooLabs\Harmony\Harmony::next()
     * @covers \WoohooLabs\Harmony\Harmony::live()
     * @covers \WoohooLabs\Harmony\Harmony::addMiddleware()
     * @expectedException \WoohooLabsTest\Harmony\Utils\Exception\TestException
     * @expectedExceptionMessage dummy2
     */
    public function testNext()
    {
        $harmony = new Harmony();
        $harmony->addMiddleware(new DummyMiddleware("dummy1"));
        $harmony->live();
        $harmony->addMiddleware(new ExceptionMiddleware("dummy2"));
        $harmony->next();
    }

    /**
     * @covers \WoohooLabs\Harmony\Harmony::next()
     * @covers \WoohooLabs\Harmony\Harmony::live()
     * @covers \WoohooLabs\Harmony\Harmony::addMiddleware()
     * @expectedException \WoohooLabsTest\Harmony\Utils\Exception\TestException
     * @expectedExceptionMessage dummy3
     */
    public function testContinueWith()
    {
        $harmony = new Harmony();
        $harmony->addMiddleware(new DummyMiddleware("dummy1"));
        $harmony->live();
        $harmony->addMiddleware(new ExceptionMiddleware("dummy2"));
        $harmony->addMiddleware(new ExceptionMiddleware("dummy3"));
        $harmony->continueWith("dummy3");
    }

    /**
     * @covers \WoohooLabs\Harmony\Harmony::setRequest()
     * @covers \WoohooLabs\Harmony\Harmony::getRequest()
     */
    public function testRequest()
    {
        $harmony = new Harmony();
        $request = new DummyServerRequest();
        $harmony->setRequest($request);

        $this->assertEquals($request, $harmony->getRequest());
    }

    /**
     * @covers \WoohooLabs\Harmony\Harmony::setResponse()
     * @covers \WoohooLabs\Harmony\Harmony::getResponse()
     */
    public function testResponse()
    {
        $harmony = new Harmony();
        $response = new DummyResponse();
        $harmony->setResponse($response);

        $this->assertEquals($response, $harmony->getResponse());
    }

    /**
     * @covers \WoohooLabs\Harmony\Harmony::setContainer()
     * @covers \WoohooLabs\Harmony\Harmony::getContainer()
     */
    public function testContainer()
    {
        $harmony = new Harmony();
        $container = new BasicContainer();
        $harmony->setContainer($container);

        $this->assertEquals($container, $harmony->getContainer());
    }

    /**
     * @covers \WoohooLabs\Harmony\Harmony::setDispatcher()
     * @covers \WoohooLabs\Harmony\Harmony::getDispatcher()
     */
    public function testDispatcher()
    {
        $harmony = new Harmony();
        $dispatcher = new CallbackDispatcher(
            function () {
            }
        );
        $harmony->setDispatcher($dispatcher);

        $this->assertEquals($dispatcher, $harmony->getDispatcher());
    }

    /**
     * @covers \WoohooLabs\Harmony\Harmony::addMiddleware()
     * @covers \WoohooLabs\Harmony\Harmony::getMiddlewares()
     * @covers \WoohooLabs\Harmony\Harmony::getMiddleware()
     */
    public function testAddMiddlewares()
    {
        $harmony = new Harmony();
        $harmony->addMiddleware(new DummyMiddleware("dummy1"));
        $harmony->addMiddleware(new DummyMiddleware("dummy2"));
        $harmony->addMiddleware(new DummyMiddleware("dummy3"));

        $this->assertEquals(3, count($harmony->getMiddlewares()));
        $this->assertInstanceOf(DummyMiddleware::class, $harmony->getMiddleware("dummy1"));
    }

    /**
     * @covers \WoohooLabs\Harmony\Harmony::addMiddleware()
     * @covers \WoohooLabs\Harmony\Harmony::getMiddleware()
     */
    public function testGetNonExistentMiddleware()
    {
        $harmony = new Harmony();
        $harmony->addMiddleware(new DummyMiddleware("dummy1"));

        $this->assertNull($harmony->getMiddleware("dummy"));
    }

    /**
     * @covers \WoohooLabs\Harmony\Harmony::addMiddleware()
     * @covers \WoohooLabs\Harmony\Harmony::removeMiddleware()
     * @covers \WoohooLabs\Harmony\Harmony::getMiddleware()
     * @covers \WoohooLabs\Harmony\Harmony::getMiddlewares()
     */
    public function testRemoveMiddleware()
    {
        $harmony = new Harmony();
        $harmony->addMiddleware(new DummyMiddleware("dummy1"));
        $this->assertInstanceOf(DummyMiddleware::class, $harmony->getMiddleware("dummy1"));

        $harmony->removeMiddleware("dummy");
        $harmony->removeMiddleware("dummy1");
        $this->assertEquals(0, count($harmony->getMiddlewares()));
    }
}
