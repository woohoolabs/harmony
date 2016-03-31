<?php
namespace WoohooLabsTest\Harmony\Middleware;

use FastRoute\Dispatcher;
use PHPUnit_Framework_TestCase;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\DiactorosResponderMiddleware;
use WoohooLabsTest\Harmony\Utils\Diactoros\DummyEmitter;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyServerRequest;

class DiactorosResponderMiddlewareTest extends PHPUnit_Framework_TestCase
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
    public function getCheckOutputStart()
    {
        $middleware = new DiactorosResponderMiddleware(null, true);
        $this->assertTrue($middleware->getCheckOutputStart());
    }

    /**
     * @test
     */
    public function setCheckOutputStart()
    {
        $middleware = new DiactorosResponderMiddleware(null, true);
        $middleware->setCheckOutputStart(false);
        $this->assertFalse($middleware->getCheckOutputStart());
    }

    /**
     * @return \WoohooLabs\Harmony\Harmony
     */
    private function createHarmony()
    {
        return new Harmony(new DummyServerRequest(), new DummyResponse());
    }
}
