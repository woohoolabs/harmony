<?php
namespace WoohooLabsTest\Harmony\Middleware;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\CallbackMiddleware;
use WoohooLabsTest\Harmony\Utils\Exception\TestException;

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
     * @expectedException \WoohooLabsTest\Harmony\Utils\Exception\TestException
     * @expectedExceptionMessage middleware1
     */
    public function testExecute()
    {
        $middleware = new CallbackMiddleware(
            "middleware1",
            function (Harmony $harmony) {
                throw new TestException("middleware1");
            }
        );

        $middleware->execute(new Harmony());
    }
}
