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
    public function testConstructWithCustomEmitter(): void
    {
        $emitter = new DummyLaminasEmitter();
        $middleware = new LaminasEmitterMiddleware($emitter);

        $returnedEmitter = $middleware->getEmitter();

        $this->assertEquals($emitter, $returnedEmitter);
    }

    public function testConstructWithDefaultEmitter(): void
    {
        $middleware = new LaminasEmitterMiddleware();

        $returnedEmitter = $middleware->getEmitter();

        $this->assertInstanceOf(SapiEmitter::class, $returnedEmitter);
    }

    public function testSetEmitter(): void
    {
        $middleware = new LaminasEmitterMiddleware();

        $middleware->setEmitter(new DummyLaminasEmitter());

        $this->assertInstanceOf(DummyLaminasEmitter::class, $middleware->getEmitter());
    }

    public function testProcess(): void
    {
        $harmony = $this->createHarmony();
        $middleware = new LaminasEmitterMiddleware(new DummyLaminasEmitter());

        $this->expectOutputString("true");

        $middleware->process($harmony->getRequest(), $harmony);
    }

    public function testIsOutputStartChecked(): void
    {
        $middleware = new LaminasEmitterMiddleware(null, true);

        $isOutputStarted = $middleware->isOutputStartChecked();

        $this->assertTrue($isOutputStarted);
    }

    public function testSetCheckOutputStart(): void
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
