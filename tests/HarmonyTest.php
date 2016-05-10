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
        $harmony->addMiddleware("dummy1", new HeaderMiddleware("dummy", "dummy"));
        $harmony->addMiddleware("dummy2", new DummyMiddleware("dummy1"));
        $harmony->addMiddleware("dummy3", new InternalServerErrorMiddleware());
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
        $harmony->addMiddleware("dummy1", new DummyMiddleware("dummy1"));
        $harmony->addMiddleware("dummy2", new InternalServerErrorMiddleware());
        $harmony->addMiddleware("dummy3", new ExceptionMiddleware("dummy3"));
        $harmony();

        $this->assertEquals(500, $harmony->getResponse()->getStatusCode());
    }

    /**
     * @test
     * @expectedException \PHPUnit_Framework_Error_Deprecated
     */
    public function deprecatedMiddlewareReturnValue()
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware("dummy1", new DummyMiddleware(""));
        $harmony->addMiddleware("dummy2", new ReturningMiddleware(null));
        $harmony->addMiddleware("dummy3", new DummyMiddleware(""));
        $harmony();
    }

    /**
     * @test
     * @expectedException \WoohooLabs\Harmony\Exception\MiddlewareReturnTypeException
     */
    public function inappropriateMiddlewareReturnValue()
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware("dummy1", new DummyMiddleware(""));
        $harmony->addMiddleware("dummy2", new ReturningMiddleware(new DummyServerRequest()));
        $harmony->addMiddleware("dummy3", new DummyMiddleware(""));
        $harmony();
    }

    /**
     * @test
     */
    public function invokeOneFinalMiddleware()
    {
        $harmony = $this->createHarmony();
        $harmony->addFinalMiddleware("dummy1", new HeaderMiddleware("dummy", "dummy"));
        $harmony->__destruct();

        $this->assertEquals(["dummy"], $harmony->getResponse()->getHeader("dummy"));
    }

    /**
     * @test
     */
    public function stopAfterFirstFinalMiddleware()
    {
        $harmony = $this->createHarmony();
        $harmony->addFinalMiddleware("dummy1", new InternalServerErrorMiddleware());
        $harmony->addFinalMiddleware("dummy2", new ExceptionMiddleware("dummy2"));
        $harmony->__destruct();
    }

    /**
     * @test
     */
    public function invokeOnlyFinalMiddleware()
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware("dummy1", new ExceptionMiddleware("dummy"));
        $harmony->addFinalMiddleware("dummy2", new DummyMiddleware());
        $harmony->__destruct();
    }

    /**
     * @test
     */
    public function invokeMultipleFinalMiddleware()
    {
        $harmony = $this->createHarmony();
        $harmony->addFinalMiddleware("dummy1", new HeaderMiddleware("dummy1", "dummy"));
        $harmony->addFinalMiddleware("dummy2", new HeaderMiddleware("dummy2", "dummy"));
        $harmony->addFinalMiddleware("dummy3", new DummyMiddleware());
        $harmony->addFinalMiddleware("dummy4", new InternalServerErrorMiddleware());
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
        $harmony->addMiddleware("dummy1", new DummyMiddleware("dummy1"));
        $harmony->addMiddleware("dummy2", new DummyMiddleware("dummy2"));
        $harmony->addMiddleware("dummy3", new DummyMiddleware("dummy3"));

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
        $middleware= new DummyMiddleware("dummy");

        $harmony = $this->createHarmony();
        $harmony->addMiddleware("dummy", $middleware);

        $this->assertEquals($middleware, $harmony->getMiddleware("dummy"));
    }

    /**
     * @test
     */
    public function removeMiddleware()
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
