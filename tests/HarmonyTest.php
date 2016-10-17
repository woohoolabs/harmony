<?php
namespace WoohooLabsTest\Harmony;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Harmony\Harmony;
use WoohooLabsTest\Harmony\Utils\Condition\StubCondition;
use WoohooLabsTest\Harmony\Utils\Middleware\ExceptionMiddleware;
use WoohooLabsTest\Harmony\Utils\Middleware\FakeMiddleware;
use WoohooLabsTest\Harmony\Utils\Middleware\HeaderMiddleware;
use WoohooLabsTest\Harmony\Utils\Middleware\InternalServerErrorMiddleware;
use WoohooLabsTest\Harmony\Utils\Middleware\ReturningMiddleware;
use WoohooLabsTest\Harmony\Utils\Middleware\SpyMiddleware;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyServerRequest;

class HarmonyTest extends TestCase
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
        $harmony->addMiddleware(new FakeMiddleware());
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
        $harmony->addMiddleware(new FakeMiddleware());
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
        $harmony->addMiddleware(new FakeMiddleware(""));
        $harmony->addMiddleware(new ReturningMiddleware(null));
        $harmony->addMiddleware(new FakeMiddleware(""));
        $harmony();
    }

    /**
     * @test
     * @expectedException \WoohooLabs\Harmony\Exception\MiddlewareWrongReturnType
     */
    public function inappropriateMiddlewareReturnValue()
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware(new FakeMiddleware(""));
        $harmony->addMiddleware(new ReturningMiddleware(new DummyServerRequest()));
        $harmony->addMiddleware(new FakeMiddleware(""));
        $harmony();
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
        $harmony->addMiddleware(new FakeMiddleware("dummy1"), "dummy1");
        $harmony->addMiddleware(new FakeMiddleware("dummy2"), "dummy2");
        $harmony->addMiddleware(new FakeMiddleware("dummy3"), "dummy3");

        $this->assertInstanceOf(FakeMiddleware::class, $harmony->getMiddleware("dummy1"));
        $this->assertInstanceOf(FakeMiddleware::class, $harmony->getMiddleware("dummy2"));
        $this->assertInstanceOf(FakeMiddleware::class, $harmony->getMiddleware("dummy3"));
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
        $middleware = new FakeMiddleware();

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
        $harmony->addMiddleware(new FakeMiddleware(), "dummy");

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

    /**
     * @test
     */
    public function invokeMiddlewareConditionally()
    {
        $middleware = new SpyMiddleware();

        $harmony = $this->createHarmony();
        $harmony->addCondition(
            new StubCondition(true),
            function (Harmony $harmony) use ($middleware) {
                $harmony->addMiddleware($middleware);
            }
        );
        $harmony();

        $this->assertTrue($middleware->isInvoked());
    }

    /**
     * @test
     */
    public function doNotInvokeMiddlewareWhenConditionIsFalse()
    {
        $middleware = new SpyMiddleware();

        $harmony = $this->createHarmony();
        $harmony->addCondition(
            new StubCondition(false),
            function (Harmony $harmony) use ($middleware) {
                $harmony->addMiddleware($middleware);
            }
        );
        $harmony();

        $this->assertFalse($middleware->isInvoked());
    }

    protected function createHarmony()
    {
        return new Harmony(new DummyServerRequest(), new DummyResponse());
    }
}
