<?php
namespace WoohooLabsTest\Harmony\Middleware;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\OutputBufferStarterMiddleware;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyServerRequest;

class OutputBufferStarterMiddlewareTest extends PHPUnit_Framework_TestCase
{
    public function testInvoke()
    {
        $harmony = $this->createHarmony();
        $middleware = new OutputBufferStarterMiddleware();

        $this->expectOutputString("");
        $middleware($harmony->getRequest(), $harmony->getResponse(), $harmony);
        echo "silent echo";
    }

    /**
     * @return \WoohooLabs\Harmony\Harmony
     */
    private function createHarmony()
    {
        return new Harmony(new DummyServerRequest(), new DummyResponse());
    }
}
