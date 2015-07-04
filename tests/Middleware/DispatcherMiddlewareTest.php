<?php
namespace WoohooLabsTest\Harmony\Middleware;

use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
use WoohooLabsTest\Harmony\Utils\Middleware\ExceptionMiddleware;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyServerRequest;

class DispatcherMiddlewareTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \WoohooLabs\Harmony\Middleware\DispatcherMiddleware::__invoke()
     */
    public function testReturningResponse()
    {
        $harmony = $this->createHarmony(
            function (ServerRequestInterface $request, ResponseInterface $response) {
                return $response->withStatus(404);
            }
        );

        $middleware = new DispatcherMiddleware();
        $middleware($harmony->getRequest(), $harmony->getResponse(), $harmony);

        $this->assertEquals(404, $harmony->getResponse()->getStatusCode());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\DispatcherMiddleware::__invoke()
     * @expectedException \WoohooLabsTest\Harmony\Utils\Exception\TestException
     * @expectedExceptionMessage next
     */
    public function testCallingNextMiddleware()
    {
        $harmony = $this->createHarmony(
            function (ServerRequestInterface $request, ResponseInterface $response) {
                return $response;
            }
        );
        $harmony->addMiddleware("exception", new ExceptionMiddleware("next"));

        $middleware = new DispatcherMiddleware();
        $middleware($harmony->getRequest(), $harmony->getResponse(), $harmony);
    }

    /**
     * @param callable $callable
     * @return \WoohooLabs\Harmony\Harmony
     */
    protected function createHarmony(callable $callable)
    {
        $request = new DummyServerRequest();
        $request = $request->withAttribute("__callable", $callable);

        return new Harmony($request, new DummyResponse());
    }
}
