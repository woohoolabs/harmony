<?php
namespace WoohooLabsTest\Harmony\Middleware;

use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Dispatcher\CallbackDispatcher;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
use WoohooLabsTest\Harmony\Utils\Middleware\ExceptionMiddleware;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyServerRequest;

class DispatcherMiddlewareTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \WoohooLabs\Harmony\Middleware\DispatcherMiddleware::__construct()
     * @covers \WoohooLabs\Harmony\Middleware\DispatcherMiddleware::getId()
     */
    public function testGetId()
    {
        $middleware = new DispatcherMiddleware();

        $this->assertEquals(DispatcherMiddleware::ID, $middleware->getId());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\DispatcherMiddleware::execute()
     */
    public function testReturningResponse()
    {
        $harmony = $this->createHarmony(
            function (ServerRequestInterface $request, ResponseInterface $response) {
                return $response->withStatus(404);
            }
        );

        $middleware = new DispatcherMiddleware();
        $middleware->execute($harmony);

        $this->assertEquals(404, $harmony->getResponse()->getStatusCode());
    }

    /**
     * @covers \WoohooLabs\Harmony\Middleware\DispatcherMiddleware::execute()
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
        $harmony->addMiddleware(new ExceptionMiddleware("next"));

        $middleware = new DispatcherMiddleware();
        $middleware->execute($harmony);
    }

    /**
     * @param \Closure $callback
     * @return \WoohooLabs\Harmony\Harmony
     */
    protected function createHarmony(\Closure $callback)
    {
        $harmony = new Harmony();
        $harmony->setRequest(new DummyServerRequest());
        $harmony->setResponse(new DummyResponse());
        $harmony->setDispatcher(new CallbackDispatcher($callback));

        return $harmony;
    }
}
