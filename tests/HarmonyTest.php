<?php
namespace WoohooLabsTest\Harmony;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Harmony\Harmony;
use WoohooLabsTest\Harmony\Utils\Middleware\DummyMiddleware;
use WoohooLabsTest\Harmony\Utils\Middleware\ExceptionMiddleware;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyServerRequest;

class HarmonyTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \WoohooLabs\Harmony\Harmony::__construct()
     * @covers \WoohooLabs\Harmony\Harmony::getRequest()
     * @covers \WoohooLabs\Harmony\Harmony::getResponse()
     */
    public function testConstruct()
    {
        $harmony = $this->createHarmony();

        $this->assertInstanceOf(DummyServerRequest::class, $harmony->getRequest());
        $this->assertInstanceOf(DummyResponse::class, $harmony->getResponse());
    }

    /**
     * @covers \WoohooLabs\Harmony\Harmony::__invoke()
     * @covers \WoohooLabs\Harmony\Harmony::addMiddleware()
     * @expectedException \WoohooLabsTest\Harmony\Utils\Exception\TestException
     * @expectedExceptionMessage dummy2
     */
    public function testInvokeNext()
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware("dummy1", new DummyMiddleware("dummy1"));
        $harmony(new DummyServerRequest(), new DummyResponse());
        $harmony->addMiddleware("dummy2", new ExceptionMiddleware("dummy2"));
        $harmony();
    }

    /**
     * @covers \WoohooLabs\Harmony\Harmony::__invoke()
     * @covers \WoohooLabs\Harmony\Harmony::addMiddleware()
     * @expectedException \WoohooLabsTest\Harmony\Utils\Exception\TestException
     * @expectedExceptionMessage dummy3
     */
    public function testSkipTo()
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware("dummy1", new DummyMiddleware("dummy1"));
        $harmony(new DummyServerRequest(), new DummyResponse());
        $harmony->addMiddleware("dummy2", new DummyMiddleware("dummy2"));
        $harmony->addMiddleware("dummy3", new ExceptionMiddleware("dummy3"));
        $harmony->skipTo("dummy3");
    }

    /**
     * @covers \WoohooLabs\Harmony\Harmony::getRequest()
     */
    public function testRequest()
    {
        $harmony = $this->createHarmony();
        $request = new DummyServerRequest();
        $harmony($request);

        $this->assertEquals($request, $harmony->getRequest());
    }

    /**
     * @covers \WoohooLabs\Harmony\Harmony::getResponse()
     */
    public function testResponse()
    {
        $harmony = $this->createHarmony();
        $response = new DummyResponse();
        $harmony(new DummyServerRequest(), $response);

        $this->assertEquals($response, $harmony->getResponse());
    }

    /**
     * @covers \WoohooLabs\Harmony\Harmony::addMiddleware()
     * @covers \WoohooLabs\Harmony\Harmony::getMiddleware()
     */
    public function testAddMiddlewares()
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware("dummy1", new DummyMiddleware("dummy1"));
        $harmony->addMiddleware("dummy2", new DummyMiddleware("dummy2"));
        $harmony->addMiddleware("dummy3", new DummyMiddleware("dummy3"));

        $this->assertInstanceOf(DummyMiddleware::class, $harmony->getMiddleware("dummy1"));
        $this->assertInstanceOf(DummyMiddleware::class, $harmony->getMiddleware("dummy2"));
        $this->assertInstanceOf(DummyMiddleware::class, $harmony->getMiddleware("dummy3"));
    }

    /**
     * @covers \WoohooLabs\Harmony\Harmony::addMiddleware()
     * @covers \WoohooLabs\Harmony\Harmony::getMiddleware()
     */
    public function testGetNonExistentMiddleware()
    {
        $harmony = $this->createHarmony();

        $this->assertNull($harmony->getMiddleware("dummy"));
    }

    /**
     * @covers \WoohooLabs\Harmony\Harmony::addMiddleware()
     * @covers \WoohooLabs\Harmony\Harmony::removeMiddleware()
     * @covers \WoohooLabs\Harmony\Harmony::getMiddleware()
     */
    public function testRemoveMiddleware()
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware("dummy1", new DummyMiddleware());
        $this->assertInstanceOf(DummyMiddleware::class, $harmony->getMiddleware("dummy1"));

        $harmony->removeMiddleware("dummy1");
        $this->assertNull($harmony->getMiddleware("dummy1"));
    }

    protected function createHarmony()
    {
        return new Harmony(new DummyServerRequest(), new DummyResponse());
    }
}
