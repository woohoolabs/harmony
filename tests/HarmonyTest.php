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
    public function testConstruct()
    {
        $harmony = $this->createHarmony();

        $this->assertInstanceOf(DummyServerRequest::class, $harmony->getRequest());
        $this->assertInstanceOf(DummyResponse::class, $harmony->getResponse());
    }

    /**
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

    public function testRequest()
    {
        $harmony = $this->createHarmony();
        $request = new DummyServerRequest();
        $harmony($request);

        $this->assertEquals($request, $harmony->getRequest());
    }

    public function testResponse()
    {
        $harmony = $this->createHarmony();
        $response = new DummyResponse();
        $harmony(new DummyServerRequest(), $response);

        $this->assertEquals($response, $harmony->getResponse());
    }

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

    public function testGetNonExistentMiddleware()
    {
        $harmony = $this->createHarmony();

        $this->assertNull($harmony->getMiddleware("dummy"));
    }

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
