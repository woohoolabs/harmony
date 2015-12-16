<?php
namespace WoohooLabsTest\Harmony;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Harmony\Harmony;
use WoohooLabsTest\Harmony\Utils\Middleware\DummyMiddleware;
use WoohooLabsTest\Harmony\Utils\Middleware\ExceptionMiddleware;
use WoohooLabsTest\Harmony\Utils\Middleware\InternalServerErrorMiddleware;
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

    /**
     * @expectedException \WoohooLabsTest\Harmony\Utils\Exception\TestException
     * @expectedExceptionMessage dummy3
     */
    public function testInvokeThreeMiddlewaresWithSuccessfulCompletion()
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware("dummy1", new DummyMiddleware("dummy1"));
        $harmony->addMiddleware("dummy2", new DummyMiddleware("dummy2"));
        $harmony->addMiddleware("dummy3", new ExceptionMiddleware("dummy3"));
        $harmony();
    }

    public function testInvokeMiddlewaresWithUnsuccessfulCompletion()
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware("dummy1", new DummyMiddleware("dummy1"));
        $harmony->addMiddleware("dummy2", new InternalServerErrorMiddleware());
        $harmony->addMiddleware("dummy3", new ExceptionMiddleware("dummy3"));
        $harmony();

        $this->assertEquals(500, $harmony->getResponse()->getStatusCode());
    }

    /**
     * @expectedException \WoohooLabsTest\Harmony\Utils\Exception\TestException
     * @expectedExceptionMessage dummy1
     */
    public function testInvokeFinalMiddleware()
    {
        $harmony = $this->createHarmony();
        $harmony->addFinalMiddleware("dummy1", new ExceptionMiddleware("dummy1"));
        $harmony();
    }

    public function testStopFinalMiddleware()
    {
        $harmony = $this->createHarmony();
        $harmony->addFinalMiddleware("dummy2", new InternalServerErrorMiddleware());
        $harmony->addFinalMiddleware("dummy2", new ExceptionMiddleware("dummy2"));
        $harmony->__destruct();
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

    public function testGetExistentMiddleware()
    {
        $middleware= new DummyMiddleware("dummy");

        $harmony = $this->createHarmony();
        $harmony->addMiddleware("dummy", $middleware);

        $this->assertEquals($middleware, $harmony->getMiddleware("dummy"));
    }

    public function testRemoveMiddleware()
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware("dummy", new DummyMiddleware());
        $this->assertInstanceOf(DummyMiddleware::class, $harmony->getMiddleware("dummy"));

        $harmony->removeMiddleware("dummy");
        $this->assertNull($harmony->getMiddleware("dummy"));
    }

    protected function createHarmony()
    {
        return new Harmony(new DummyServerRequest(), new DummyResponse());
    }
}
