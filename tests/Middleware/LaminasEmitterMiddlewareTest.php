<?php

declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Middleware;

use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use PHPUnit\Framework\TestCase;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\LaminasEmitterMiddleware;
use WoohooLabs\Harmony\Tests\Utils\Emitter\DummyLaminasEmitter;
use WoohooLabs\Harmony\Tests\Utils\Psr7\DummyResponse;
use WoohooLabs\Harmony\Tests\Utils\Psr7\DummyServerRequest;

class LaminasEmitterMiddlewareTest extends TestCase
{
    /**
     * @test
     */
    public function constructWithCustomEmitter(): void
    {
        $emitter = new DummyLaminasEmitter();
        $middleware = new LaminasEmitterMiddleware($emitter);

        $returnedEmitter = $middleware->getEmitter();

        $this->assertEquals($emitter, $returnedEmitter);
    }

    /**
     * @test
     */
    public function constructWithDefaultEmitter(): void
    {
        $middleware = new LaminasEmitterMiddleware();

        $returnedEmitter = $middleware->getEmitter();

        $this->assertInstanceOf(SapiEmitter::class, $returnedEmitter);
    }

    /**
     * @test
     */
    public function setEmitter(): void
    {
        $middleware = new LaminasEmitterMiddleware();

        $middleware->setEmitter(new DummyLaminasEmitter());

        $this->assertInstanceOf(DummyLaminasEmitter::class, $middleware->getEmitter());
    }

    /**
     * @test
     */
    public function process(): void
    {
        $harmony = $this->createHarmony();
        $middleware = new LaminasEmitterMiddleware(new DummyLaminasEmitter());

        $this->expectOutputString("true");

        $middleware->process($harmony->getRequest(), $harmony);
    }

    /**
     * @test
     */
    public function isOutputStartChecked(): void
    {
        $middleware = new LaminasEmitterMiddleware(null, true);

        $isOutputStarted = $middleware->isOutputStartChecked();

        $this->assertTrue($isOutputStarted);
    }

    /**
     * @test
     */
    public function setCheckOutputStart(): void
    {
        $middleware = new LaminasEmitterMiddleware(null, true);

        $middleware->setCheckOutputStart(false);

        $this->assertFalse($middleware->isOutputStartChecked());
    }

    private function createHarmony(): Harmony
    {
        return new Harmony(new DummyServerRequest(), new DummyResponse());
    }
}
