<?php
namespace WoohooLabsTest\Harmony\Middleware;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\CallbackMiddleware;

class CallbackMiddlewareTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \WoohooLabs\Harmony\Middleware\CallbackMiddleware::__construct()
     * @covers \WoohooLabs\Harmony\Middleware\CallbackMiddleware::getId()
     */
    public function testGetId()
    {
        $middleware = new CallbackMiddleware(
            "middleware1",
            function (Harmony $harmony) {
            }
        );

        $this->assertEquals("middleware1", $middleware->getId());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\CallbackMiddleware::__construct()
     * @covers \WoohooLabs\Harmony\Middleware\CallbackMiddleware::execute()
     */
    public function testExecute()
    {
        $isSuccess = false;

        $middleware = new CallbackMiddleware(
            "middleware1",
            function (Harmony $harmony) use (&$isSuccess) {
                $isSuccess = true;
            }
        );
        $middleware->execute(new Harmony());
        $this->assertEquals(true, $isSuccess);
    }
}
