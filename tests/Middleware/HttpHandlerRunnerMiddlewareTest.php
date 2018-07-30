<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Middleware;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\HttpHandlerRunnerMiddleware;
use WoohooLabs\Harmony\Tests\Utils\Emitter\DummyHttpHandlerRunnerEmitter;
use WoohooLabs\Harmony\Tests\Utils\Psr7\DummyResponse;
use WoohooLabs\Harmony\Tests\Utils\Psr7\DummyServerRequest;

class HttpHandlerRunnerMiddlewareTest extends TestCase
{
    /**
     * @test
     */
    public function construct()
    {
        $middleware = new HttpHandlerRunnerMiddleware(new DummyHttpHandlerRunnerEmitter());

        $this->assertEquals(DummyHttpHandlerRunnerEmitter::class, get_class($middleware->getEmitter()));
    }

    /**
     * @test
     */
    public function setEmitter()
    {
        $middleware = new HttpHandlerRunnerMiddleware(null);
        $middleware->setEmitter(new DummyHttpHandlerRunnerEmitter());

        $this->assertEquals(DummyHttpHandlerRunnerEmitter::class, get_class($middleware->getEmitter()));
    }

    /**
     * @test
     */
    public function invoke()
    {
        $harmony = $this->createHarmony();
        $middleware = new HttpHandlerRunnerMiddleware(new DummyHttpHandlerRunnerEmitter());

        $this->expectOutputString("true");
        $middleware->process($harmony->getRequest(), $harmony);
    }

    /**
     * @test
     */
    public function isOutputStartChecked()
    {
        $middleware = new HttpHandlerRunnerMiddleware(null, true);
        $this->assertTrue($middleware->isOutputStartChecked());
    }

    /**
     * @test
     */
    public function setCheckOutputStart()
    {
        $middleware = new HttpHandlerRunnerMiddleware(null, true);
        $middleware->setCheckOutputStart(false);
        $this->assertFalse($middleware->isOutputStartChecked());
    }

    private function createHarmony(): Harmony
    {
        return new Harmony(new DummyServerRequest(), new DummyResponse());
    }
}
