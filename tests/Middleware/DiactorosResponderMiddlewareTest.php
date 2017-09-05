<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Middleware;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware;
use WoohooLabs\Harmony\Tests\Utils\Diactoros\DummyEmitter;
use WoohooLabs\Harmony\Tests\Utils\Psr7\DummyResponse;
use WoohooLabs\Harmony\Tests\Utils\Psr7\DummyServerRequest;

class DiactorosResponderMiddlewareTest extends TestCase
{
    /**
     * @test
     */
    public function construct()
    {
        $middleware = new DiactorosResponderMiddleware(new DummyEmitter());

        $this->assertInstanceOf(DummyEmitter::class, $middleware->getEmitter());
    }

    /**
     * @test
     */
    public function setEmitter()
    {
        $middleware = new DiactorosResponderMiddleware(null);
        $middleware->setEmitter(new DummyEmitter());

        $this->assertInstanceOf(DummyEmitter::class, $middleware->getEmitter());
    }

    /**
     * @test
     */
    public function invoke()
    {
        $harmony = $this->createHarmony();
        $middleware = new DiactorosResponderMiddleware(new DummyEmitter());

        $this->expectOutputString("true");
        $middleware($harmony->getRequest(), $harmony->getResponse(), $harmony);
    }

    /**
     * @test
     */
    public function isOutputStartChecked()
    {
        $middleware = new DiactorosResponderMiddleware(null, true);
        $this->assertTrue($middleware->isOutputStartChecked());
    }

    /**
     * @test
     */
    public function setCheckOutputStart()
    {
        $middleware = new DiactorosResponderMiddleware(null, true);
        $middleware->setCheckOutputStart(false);
        $this->assertFalse($middleware->isOutputStartChecked());
    }

    private function createHarmony(): Harmony
    {
        return new Harmony(new DummyServerRequest(), new DummyResponse());
    }
}
