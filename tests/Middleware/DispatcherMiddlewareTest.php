<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Middleware;

use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Container\BasicContainer;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
use WoohooLabs\Harmony\Tests\Utils\Controller\ExceptionController;
use WoohooLabs\Harmony\Tests\Utils\Controller\InvokableExceptionController;
use WoohooLabs\Harmony\Tests\Utils\Exception\TestException;
use WoohooLabs\Harmony\Tests\Utils\Middleware\ExceptionMiddleware;
use WoohooLabs\Harmony\Tests\Utils\Psr7\DummyResponse;
use WoohooLabs\Harmony\Tests\Utils\Psr7\DummyServerRequest;

class DispatcherMiddlewareTest extends TestCase
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
     * @expectedException \WoohooLabs\Harmony\Tests\Utils\Exception\TestException
     * @expectedExceptionMessage next
     */
    public function callNextMiddleware()
    {
        $harmony = $this->createHarmony(
            function (ServerRequestInterface $request, ResponseInterface $response) {
                return $response;
            }
        );
        $harmony->addMiddleware(new ExceptionMiddleware("next"), "exception");

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
        } catch (Exception $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @test
     * @expectedException \WoohooLabs\Harmony\Tests\Utils\Exception\TestException
     */
    public function dispatchArrayCallable()
    {
        $request = new DummyServerRequest();
        $request = $request->withAttribute("__action", [ExceptionController::class, "dummyAction"]);

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
     * @expectedException \WoohooLabs\Harmony\Tests\Utils\Exception\TestException
     */
    public function dispatchAnonymousFunction()
    {
        $request = new DummyServerRequest();
        $callable = function (ServerRequestInterface $request, ResponseInterface $response) {
            throw new TestException();
        };
        $request = $request->withAttribute("__action", $callable);

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
     * @expectedException \WoohooLabs\Harmony\Tests\Utils\Exception\TestException
     */
    public function dispatchInvokableClass()
    {
        $request = new DummyServerRequest();
        $request = $request->withAttribute("__action", InvokableExceptionController::class);

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
        $this->assertEquals("__action", $middleware->getActionAttributeName());
    }

    /**
     * @test
     */
    public function getHandlerAttribute()
    {
        $middleware = new DispatcherMiddleware();
        $middleware->setActionAttributeName("action");
        $this->assertEquals("action", $middleware->getActionAttributeName());
    }

    /**
     * @param mixed $callable
     * @param string $attributeName
     * @return \WoohooLabs\Harmony\Harmony
     */
    protected function createHarmony($callable, $attributeName = "__action")
    {
        $request = new DummyServerRequest();
        $request = $request->withAttribute($attributeName, $callable);

        return new Harmony($request, new DummyResponse());
    }
}
