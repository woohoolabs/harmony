<?php

declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Tests\Utils\Condition\StubCondition;
use WoohooLabs\Harmony\Tests\Utils\Middleware\ExceptionMiddleware;
use WoohooLabs\Harmony\Tests\Utils\Middleware\FakeMiddleware;
use WoohooLabs\Harmony\Tests\Utils\Middleware\HeaderMiddleware;
use WoohooLabs\Harmony\Tests\Utils\Middleware\InternalServerErrorMiddleware;
use WoohooLabs\Harmony\Tests\Utils\Middleware\SpyMiddleware;
use WoohooLabs\Harmony\Tests\Utils\Psr7\DummyResponse;
use WoohooLabs\Harmony\Tests\Utils\Psr7\DummyServerRequest;

class HarmonyTest extends TestCase
{
    /**
     * @test
     */
    public function construct(): void
    {
        $harmony = $this->createHarmony();

        $request = $harmony->getRequest();

        $this->assertInstanceOf(DummyServerRequest::class, $request);
    }

    /**
     * @test
     */
    public function runAllMiddleware(): void
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware(new HeaderMiddleware("dummy", "dummy"));
        $harmony->addMiddleware(new FakeMiddleware());
        $harmony->addMiddleware(new InternalServerErrorMiddleware(new DummyResponse()));

        $response = $harmony->run();

        $this->assertEquals(["dummy"], $response->getHeader("dummy"));
        $this->assertEquals(500, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function invokeAllMiddleware(): void
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware(new HeaderMiddleware("dummy", "dummy"));
        $harmony->addMiddleware(new FakeMiddleware());
        $harmony->addMiddleware(new InternalServerErrorMiddleware(new DummyResponse()));

        $response = $harmony->run();

        $this->assertEquals(["dummy"], $response->getHeader("dummy"));
        $this->assertEquals(500, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function returnAfterSecondMiddleware(): void
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware(new FakeMiddleware());
        $harmony->addMiddleware(new InternalServerErrorMiddleware(new DummyResponse()));
        $harmony->addMiddleware(new ExceptionMiddleware());

        $response = $harmony->run();

        $this->assertEquals(500, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function getRequest(): void
    {
        $harmony = $this->createHarmony();
        $request = new DummyServerRequest();

        $harmony->run();

        $this->assertEquals($request, $harmony->getRequest());
    }

    /**
     * @test
     */
    public function getResponse(): void
    {
        $harmony = $this->createHarmony();
        $response = new DummyResponse();

        $result = $harmony->run();

        $this->assertEquals($response, $result);
    }

    /**
     * @test
     */
    public function addMiddleware(): void
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware(new FakeMiddleware("dummy1"), "dummy1");
        $harmony->addMiddleware(new FakeMiddleware("dummy2"), "dummy2");
        $harmony->addMiddleware(new FakeMiddleware("dummy3"), "dummy3");

        $middleware1 = $harmony->getMiddleware("dummy1");
        $middleware2 = $harmony->getMiddleware("dummy2");
        $middleware3 = $harmony->getMiddleware("dummy3");

        $this->assertInstanceOf(FakeMiddleware::class, $middleware1);
        $this->assertInstanceOf(FakeMiddleware::class, $middleware2);
        $this->assertInstanceOf(FakeMiddleware::class, $middleware3);
    }

    /**
     * @test
     */
    public function runMiddlewareAfterConditionalMiddleware(): void
    {
        $middlewareCond = new SpyMiddleware();
        $middlewareSecond = new SpyMiddleware();

        $harmony = $this->createHarmony();
        $harmony->addCondition(
            new StubCondition(true),
            static function (Harmony $harmony) use ($middlewareCond): void {
                $harmony->addMiddleware($middlewareCond);
            }
        );
        $harmony->addMiddleware($middlewareSecond);

        $harmony->run();

        $this->assertTrue($middlewareCond->isInvoked());
        $this->assertTrue($middlewareSecond->isInvoked());
    }

    /**
     * @test
     */
    public function runMiddlewareAfterConditionalMiddlewareWhenFalse(): void
    {
        $conditionalMiddleware = new SpyMiddleware();
        $middleware = new SpyMiddleware();

        $harmony = $this->createHarmony();
        $harmony->addCondition(
            new StubCondition(false),
            static function (Harmony $harmony) use ($conditionalMiddleware): void {
                $harmony->addMiddleware($conditionalMiddleware);
            }
        );
        $harmony->addMiddleware($middleware);

        $harmony->run();

        $this->assertFalse($conditionalMiddleware->isInvoked());
        $this->assertTrue($middleware->isInvoked());
    }

    /**
     * @test
     */
    public function runMiddlewareAfterNestedConditionalMiddleware(): void
    {
        $middlewareInCondition = new SpyMiddleware();
        $middlewareInCondition2 = new SpyMiddleware();
        $middlewareInCondition3 = new SpyMiddleware();
        $middleware = new SpyMiddleware();

        $harmony = $this->createHarmony();
        $harmony->addCondition(
            new StubCondition(true),
            static function (Harmony $harmony) use ($middlewareInCondition, $middlewareInCondition2, $middlewareInCondition3): void {
                $harmony->addMiddleware($middlewareInCondition);
                $harmony->addCondition(
                    new StubCondition(true),
                    static function (Harmony $harmony) use ($middlewareInCondition2): void {
                        $harmony->addMiddleware($middlewareInCondition2);
                    }
                );
                $harmony->addMiddleware($middlewareInCondition3);
            }
        );
        $harmony->addMiddleware($middleware);

        $harmony->run();

        $this->assertTrue($middlewareInCondition->isInvoked());
        $this->assertTrue($middlewareInCondition2->isInvoked());
        $this->assertTrue($middlewareInCondition3->isInvoked());
        $this->assertTrue($middleware->isInvoked());
    }

    /**
     * @test
     */
    public function runMiddlewareAfterNestedConditionalMiddlewareWhenFalse(): void
    {
        $middlewareInCondition = new SpyMiddleware();
        $middlewareInCondition2 = new SpyMiddleware();
        $middlewareInCondition3 = new SpyMiddleware();
        $middleware = new SpyMiddleware();

        $harmony = $this->createHarmony();
        $harmony->addCondition(
            new StubCondition(false),
            static function (Harmony $harmony) use ($middlewareInCondition, $middlewareInCondition2, $middlewareInCondition3): void {
                $harmony->addMiddleware($middlewareInCondition);
                $harmony->addCondition(
                    new StubCondition(true),
                    static function (Harmony $harmony) use ($middlewareInCondition2): void {
                        $harmony->addMiddleware($middlewareInCondition2);
                    }
                );
                $harmony->addMiddleware($middlewareInCondition3);
            }
        );
        $harmony->addMiddleware($middleware);

        $harmony->run();

        $this->assertFalse($middlewareInCondition->isInvoked());
        $this->assertFalse($middlewareInCondition2->isInvoked());
        $this->assertFalse($middlewareInCondition3->isInvoked());
        $this->assertTrue($middleware->isInvoked());
    }

    /**
     * @test
     */
    public function runMiddlewareAfterNestedConditionalMiddlewareWhenInnerFalse(): void
    {
        $middlewareInCondition = new SpyMiddleware();
        $middlewareInCondition2 = new SpyMiddleware();
        $middlewareInCondition3 = new SpyMiddleware();
        $middleware = new SpyMiddleware();

        $harmony = $this->createHarmony();
        $harmony->addCondition(
            new StubCondition(true),
            static function (Harmony $harmony) use ($middlewareInCondition, $middlewareInCondition2, $middlewareInCondition3): void {
                $harmony->addMiddleware($middlewareInCondition);
                $harmony->addCondition(
                    new StubCondition(false),
                    static function (Harmony $harmony) use ($middlewareInCondition2): void {
                        $harmony->addMiddleware($middlewareInCondition2);
                    }
                );
                $harmony->addMiddleware($middlewareInCondition3);
            }
        );
        $harmony->addMiddleware($middleware);

        $harmony->run();

        $this->assertTrue($middlewareInCondition->isInvoked());
        $this->assertFalse($middlewareInCondition2->isInvoked());
        $this->assertTrue($middlewareInCondition3->isInvoked());
        $this->assertTrue($middleware->isInvoked());
    }

    /**
     * @test
     */
    public function getNonExistentMiddleware(): void
    {
        $harmony = $this->createHarmony();

        $middleware = $harmony->getMiddleware("dummy");

        $this->assertNull($middleware);
    }

    /**
     * @test
     */
    public function getExistentMiddleware(): void
    {
        $middleware = new FakeMiddleware();
        $harmony = $this->createHarmony();
        $harmony->addMiddleware($middleware, "dummy");

        $returnedMiddleware = $harmony->getMiddleware("dummy");

        $this->assertEquals($middleware, $returnedMiddleware);
    }

    /**
     * @test
     */
    public function invokeMiddlewareConditionally(): void
    {
        $middleware = new SpyMiddleware();
        $harmony = $this->createHarmony();
        $harmony->addCondition(
            new StubCondition(true),
            static function (Harmony $harmony) use ($middleware): void {
                $harmony->addMiddleware($middleware);
            }
        );

        $harmony->run();

        $this->assertTrue($middleware->isInvoked());
    }

    /**
     * @test
     */
    public function doNotInvokeMiddlewareWhenConditionIsFalse(): void
    {
        $middleware = new SpyMiddleware();
        $harmony = $this->createHarmony();
        $harmony->addCondition(
            new StubCondition(false),
            static function (Harmony $harmony) use ($middleware): void {
                $harmony->addMiddleware($middleware);
            }
        );

        $harmony->run();

        $this->assertFalse($middleware->isInvoked());
    }

    protected function createHarmony(): Harmony
    {
        return new Harmony(new DummyServerRequest(), new DummyResponse());
    }
}
