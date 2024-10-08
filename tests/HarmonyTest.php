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
    public function testConstruct(): void
    {
        $harmony = $this->createHarmony();

        $request = $harmony->getRequest();

        $this->assertInstanceOf(DummyServerRequest::class, $request);
    }

    public function testRunAllMiddleware(): void
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware(new HeaderMiddleware("dummy", "dummy"));
        $harmony->addMiddleware(new FakeMiddleware());
        $harmony->addMiddleware(new InternalServerErrorMiddleware(new DummyResponse()));

        $response = $harmony->run();

        $this->assertEquals(["dummy"], $response->getHeader("dummy"));
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testInvokeAllMiddleware(): void
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware(new HeaderMiddleware("dummy", "dummy"));
        $harmony->addMiddleware(new FakeMiddleware());
        $harmony->addMiddleware(new InternalServerErrorMiddleware(new DummyResponse()));

        $response = $harmony->run();

        $this->assertEquals(["dummy"], $response->getHeader("dummy"));
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testReturnAfterSecondMiddleware(): void
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware(new FakeMiddleware());
        $harmony->addMiddleware(new InternalServerErrorMiddleware(new DummyResponse()));
        $harmony->addMiddleware(new ExceptionMiddleware());

        $response = $harmony->run();

        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testReturnResponseWhenConditionIsInvolved(): void
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware(new FakeMiddleware());
        $harmony->addCondition(
            new StubCondition(true),
            static fn (Harmony $harmony): Harmony => $harmony->addMiddleware(new InternalServerErrorMiddleware(new DummyResponse()))
        );
        $middlewareSecond = new SpyMiddleware();
        $harmony->addMiddleware($middlewareSecond);

        $harmony->run();

        $this->assertFalse($middlewareSecond->isInvoked());
    }

    public function testReturnResponseWhenMultipleConditionsAreInvolved(): void
    {
        $harmony = $this->createHarmony();
        $harmony->addMiddleware(new FakeMiddleware());
        $middlewareFirst = new SpyMiddleware();
        $harmony->addMiddleware($middlewareFirst);

        $middlewareSecond = new SpyMiddleware();
        $harmony->addCondition(
            new StubCondition(true),
            static fn (Harmony $harmony): Harmony => $harmony->addMiddleware($middlewareSecond)
        );

        $harmony->addCondition(
            new StubCondition(true),
            static fn (Harmony $harmony): Harmony => $harmony->addMiddleware(new InternalServerErrorMiddleware(new DummyResponse()))
        );

        $middlewareThird = new SpyMiddleware();
        $harmony->addMiddleware($middlewareThird);

        $harmony->run();

        $this->assertTrue($middlewareFirst->isInvoked());
        $this->assertTrue($middlewareSecond->isInvoked());
        $this->assertFalse($middlewareThird->isInvoked());
    }

    public function testGetRequest(): void
    {
        $harmony = $this->createHarmony();
        $request = new DummyServerRequest();

        $harmony->run();

        $this->assertEquals($request, $harmony->getRequest());
    }

    public function testGetResponse(): void
    {
        $harmony = $this->createHarmony();
        $response = new DummyResponse();

        $result = $harmony->run();

        $this->assertEquals($response, $result);
    }

    public function testAddMiddleware(): void
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

    public function testRunMiddlewareAfterConditionalMiddleware(): void
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

    public function testRunMiddlewareAfterConditionalMiddlewareWhenFalse(): void
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

    public function testRunMiddlewareAfterNestedConditionalMiddleware(): void
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

    public function testRunMiddlewareAfterNestedConditionalMiddlewareWhenFalse(): void
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

    public function testRunMiddlewareAfterNestedConditionalMiddlewareWhenInnerFalse(): void
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

    public function testGetNonExistentMiddleware(): void
    {
        $harmony = $this->createHarmony();

        $middleware = $harmony->getMiddleware("dummy");

        $this->assertNull($middleware);
    }

    public function testGetExistentMiddleware(): void
    {
        $middleware = new FakeMiddleware();
        $harmony = $this->createHarmony();
        $harmony->addMiddleware($middleware, "dummy");

        $returnedMiddleware = $harmony->getMiddleware("dummy");

        $this->assertEquals($middleware, $returnedMiddleware);
    }

    public function testInvokeMiddlewareConditionally(): void
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

    public function testDoNotInvokeMiddlewareWhenConditionIsFalse(): void
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
