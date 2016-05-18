<?php
namespace WoohooLabsTest\Harmony;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Harmony\Harmony;
use WoohooLabsTest\Harmony\Utils\Middleware\DummyMiddleware;
use WoohooLabsTest\Harmony\Utils\Middleware\ExceptionMiddleware;
use WoohooLabsTest\Harmony\Utils\Middleware\HeaderMiddleware;
use WoohooLabsTest\Harmony\Utils\Middleware\InternalServerErrorMiddleware;
use WoohooLabsTest\Harmony\Utils\Middleware\ReturningMiddleware;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyServerRequest;

class HarmonyTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function construct()
    {
        $harmony = $this->createHarmony();

        $this->assertInstanceOf(DummyServerRequest::class, $harmony->getRequest());
        $this->assertInstanceOf(DummyResponse::class, $harmony->getResponse());
    }

    /**
     * @test
     */
    public function invokeAllMiddleware()
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware(new HeaderMiddleware("dummy", "dummy"));
        $harmony->addMiddleware(new DummyMiddleware());
        $harmony->addMiddleware(new InternalServerErrorMiddleware());
        $harmony();

        $this->assertEquals(["dummy"], $harmony->getResponse()->getHeader("dummy"));
        $this->assertEquals(500, $harmony->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function returnAfterSecondMiddleware()
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware(new DummyMiddleware());
        $harmony->addMiddleware(new InternalServerErrorMiddleware());
        $harmony->addMiddleware(new ExceptionMiddleware());
        $harmony();

        $this->assertEquals(500, $harmony->getResponse()->getStatusCode());
    }

    /**
     * @test
     * @expectedException \WoohooLabs\Harmony\Exception\MiddlewareWrongReturnType
     */
    public function nullMiddlewareReturnValue()
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware(new DummyMiddleware(""));
        $harmony->addMiddleware(new ReturningMiddleware(null));
        $harmony->addMiddleware(new DummyMiddleware(""));
        $harmony();
    }

    /**
     * @test
     * @expectedException \WoohooLabs\Harmony\Exception\MiddlewareWrongReturnType
     */
    public function inappropriateMiddlewareReturnValue()
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware(new DummyMiddleware(""));
        $harmony->addMiddleware(new ReturningMiddleware(new DummyServerRequest()));
        $harmony->addMiddleware(new DummyMiddleware(""));
        $harmony();
    }

    /**
     * @test
     */
    public function invokeOneFinalMiddleware()
    {
        $harmony = $this->createHarmony();
        $harmony->addFinalMiddleware(new HeaderMiddleware("dummy", "dummy"));
        $harmony->__destruct();

        $this->assertEquals(["dummy"], $harmony->getResponse()->getHeader("dummy"));
    }

    /**
     * @test
     */
    public function stopAfterFirstFinalMiddleware()
    {
        $harmony = $this->createHarmony();
        $harmony->addFinalMiddleware(new InternalServerErrorMiddleware());
        $harmony->addFinalMiddleware(new ExceptionMiddleware());
        $harmony->__destruct();
    }

    /**
     * @test
     */
    public function invokeOnlyFinalMiddleware()
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware(new ExceptionMiddleware());
        $harmony->addFinalMiddleware(new DummyMiddleware());
        $harmony->__destruct();
    }

    /**
     * @test
     */
    public function invokeMultipleFinalMiddleware()
    {
        $harmony = $this->createHarmony();
        $harmony->addFinalMiddleware(new HeaderMiddleware("dummy1", "dummy"));
        $harmony->addFinalMiddleware(new HeaderMiddleware("dummy2", "dummy"));
        $harmony->addFinalMiddleware(new DummyMiddleware());
        $harmony->addFinalMiddleware(new InternalServerErrorMiddleware());
        $harmony->__destruct();

        $this->assertEquals(["dummy"], $harmony->getResponse()->getHeader("dummy1"));
        $this->assertEquals(["dummy"], $harmony->getResponse()->getHeader("dummy2"));
        $this->assertEquals(500, $harmony->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function getRequest()
    {
        $harmony = $this->createHarmony();
        $request = new DummyServerRequest();
        $harmony($request);

        $this->assertEquals($request, $harmony->getRequest());
    }

    /**
     * @test
     */
    public function getResponse()
    {
        $harmony = $this->createHarmony();
        $response = new DummyResponse();
        $harmony(new DummyServerRequest(), $response);

        $this->assertEquals($response, $harmony->getResponse());
    }

    /**
     * @test
     */
    public function addMiddleware()
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware(new DummyMiddleware("dummy1"), "dummy1");
        $harmony->addMiddleware(new DummyMiddleware("dummy2"), "dummy2");
        $harmony->addMiddleware(new DummyMiddleware("dummy3"), "dummy3");

        $this->assertInstanceOf(DummyMiddleware::class, $harmony->getMiddleware("dummy1"));
        $this->assertInstanceOf(DummyMiddleware::class, $harmony->getMiddleware("dummy2"));
        $this->assertInstanceOf(DummyMiddleware::class, $harmony->getMiddleware("dummy3"));
    }

    /**
     * @test
     */
    public function getNonExistentMiddleware()
    {
        $harmony = $this->createHarmony();

        $this->assertNull($harmony->getMiddleware("dummy"));
    }

    /**
     * @test
     */
    public function getExistentMiddleware()
    {
        $middleware= new DummyMiddleware();

        $harmony = $this->createHarmony();
        $harmony->addMiddleware($middleware, "dummy");

        $this->assertEquals($middleware, $harmony->getMiddleware("dummy"));
    }

    /**
     * @test
     */
    public function removeMiddleware()
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware(new DummyMiddleware(), "dummy");

        $harmony->removeMiddleware("dummy");
        $this->assertNull($harmony->getMiddleware("dummy"));
    }

    /**
     * @test
     * @expectedException \WoohooLabs\Harmony\Exception\MiddlewareNotExists
     */
    public function removeNonExistentMiddleware()
    {
        $harmony = $this->createHarmony();

        $harmony->removeMiddleware("dummy");
    }

    protected function createHarmony()
    {
        return new Harmony(new DummyServerRequest(), new DummyResponse());
    }
}
