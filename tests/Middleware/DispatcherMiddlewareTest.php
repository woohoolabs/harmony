<?php
namespace WoohooLabsTest\Harmony\Middleware;

use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Container\BasicContainer;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
use WoohooLabsTest\Harmony\Utils\Controller\ExceptionController;
use WoohooLabsTest\Harmony\Utils\Controller\InvokableExceptionController;
use WoohooLabsTest\Harmony\Utils\Exception\TestException;
use WoohooLabsTest\Harmony\Utils\Middleware\ExceptionMiddleware;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyServerRequest;

class DispatcherMiddlewareTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function returnResponse()
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
     * @test
     * @expectedException \WoohooLabsTest\Harmony\Utils\Exception\TestException
     * @expectedExceptionMessage next
     */
    public function callNextMiddleware()
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
     * @test
     */
    public function invokeNextCallable()
    {
        $middleware = new DispatcherMiddleware();
        try {
            $middleware(
                new DummyServerRequest(),
                new DummyResponse(),
                function () {
                }
            );
        } catch (\Exception $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @test
     * @expectedException \WoohooLabsTest\Harmony\Utils\Exception\TestException
     */
    public function dispatchArrayCallable()
    {
        $request = new DummyServerRequest();
        $request = $request->withAttribute("__callable", [ExceptionController::class, "dummyAction"]);

        $middleware = new DispatcherMiddleware();

        $middleware(
            $request,
            new DummyResponse(),
            function () {
            }
        );
    }

    /**
     * @test
     * @expectedException \WoohooLabsTest\Harmony\Utils\Exception\TestException
     */
    public function dispatchAnonymousFunction()
    {
        $request = new DummyServerRequest();
        $callable = function (ServerRequestInterface $request, ResponseInterface $response) {
            throw new TestException();
        };
        $request = $request->withAttribute("__callable", $callable);

        $middleware = new DispatcherMiddleware();

        $middleware(
            $request,
            new DummyResponse(),
            function () {
            }
        );
    }

    /**
     * @test
     * @expectedException \WoohooLabsTest\Harmony\Utils\Exception\TestException
     */
    public function dispatchInvokableClass()
    {
        $request = new DummyServerRequest();
        $request = $request->withAttribute("__callable", InvokableExceptionController::class);

        $middleware = new DispatcherMiddleware();

        $middleware(
            $request,
            new DummyResponse(),
            function () {
            }
        );
    }

    /**
     * @test
     */
    public function getContainer()
    {
        $middleware = new DispatcherMiddleware(new BasicContainer());
        $this->assertInstanceOf(BasicContainer::class, $middleware->getContainer());
    }

    /**
     * @test
     */
    public function setContainer()
    {
        $middleware = new DispatcherMiddleware();
        $middleware->setContainer(new BasicContainer());
        $this->assertInstanceOf(BasicContainer::class, $middleware->getContainer());
    }

    /**
     * @test
     */
    public function getDefaultHandlerAttribute()
    {
        $middleware = new DispatcherMiddleware();
        $middleware->setContainer(new BasicContainer());
        $this->assertEquals("__callable", $middleware->getHandlerAttribute());
    }

    /**
     * @test
     */
    public function getHandlerAttribute()
    {
        $middleware = new DispatcherMiddleware();
        $middleware->setHandlerAttribute("action");
        $this->assertEquals("action", $middleware->getHandlerAttribute());
    }

    /**
     * @param mixed $callable
     * @param string $attributeName
     * @return \WoohooLabs\Harmony\Harmony
     */
    protected function createHarmony($callable, $attributeName = "__callable")
    {
        $request = new DummyServerRequest();
        $request = $request->withAttribute($attributeName, $callable);

        return new Harmony($request, new DummyResponse());
    }
}
