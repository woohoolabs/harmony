<?php
namespace WoohooLabsTest\Harmony\Middleware;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\OutputBufferResponderMiddleware;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyServerRequest;

class OutputBufferResponderMiddlewareTest extends PHPUnit_Framework_TestCase
{
    public function testFlushingInvoke()
    {
        $harmony = $this->createHarmony();
        $middleware = new OutputBufferResponderMiddleware(false);

        $this->expectOutputString("loud echo");
        ob_start();
        echo "loud echo";
        $middleware($harmony->getRequest(), $harmony->getResponse(), $harmony);
    }

    public function testClearingInvoke()
    {
        $harmony = $this->createHarmony();
        $middleware = new OutputBufferResponderMiddleware(true);

        $this->expectOutputString("");
        ob_start();
        echo "silent echo";
        $middleware($harmony->getRequest(), $harmony->getResponse(), $harmony);
    }

    public function testIsOnlyClearBuffer()
    {
        $middleware = new OutputBufferResponderMiddleware(true);
        $this->assertTrue($middleware->isOnlyClearBuffer());
    }

    /**
     * @return \WoohooLabs\Harmony\Harmony
     */
    private function createHarmony()
    {
        return new Harmony(new DummyServerRequest(), new DummyResponse());
    }
}
