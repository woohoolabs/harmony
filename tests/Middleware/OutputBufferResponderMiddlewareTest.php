<?php
namespace WoohooLabsTest\Harmony\Middleware;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\OutputBufferResponderMiddleware;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyServerRequest;

class OutputBufferResponderMiddlewareTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function invokeWithFlush()
    {
        $harmony = $this->createHarmony();
        $middleware = new OutputBufferResponderMiddleware(false);

        $this->expectOutputString("loud echo");
        ob_start();
        echo "loud echo";
        $middleware($harmony->getRequest(), $harmony->getResponse(), $harmony);
    }

    /**
     * @test
     */
    public function InvokeWithClear()
    {
        $harmony = $this->createHarmony();
        $middleware = new OutputBufferResponderMiddleware(true);

        $this->expectOutputString("");
        ob_start();
        echo "silent echo";
        $middleware($harmony->getRequest(), $harmony->getResponse(), $harmony);
    }

    /**
     * @test
     */
    public function isOnlyClearBuffer()
    {
        $middleware = new OutputBufferResponderMiddleware(true);
        $this->assertTrue($middleware->isOnlyClearBuffer());
    }

    /**
     * @test
     */
    public function setOnlyClearBuffer()
    {
        $middleware = new OutputBufferResponderMiddleware(true);
        $middleware->setOnlyClearBuffer(false);
        $this->assertFalse($middleware->isOnlyClearBuffer());
    }

    /**
     * @return \WoohooLabs\Harmony\Harmony
     */
    private function createHarmony()
    {
        return new Harmony(new DummyServerRequest(), new DummyResponse());
    }
}
