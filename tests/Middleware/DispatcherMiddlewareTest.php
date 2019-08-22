<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Middleware;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WoohooLabs\Harmony\Container\BasicContainer;
use WoohooLabs\Harmony\Exception\DispatcherException;
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
    public function returnResponse(): void
    {
        $harmony = $this->createHarmonyWithAction(
            static function (ServerRequestInterface $request, ResponseInterface $response) {
                return $response->withStatus(404);
            }
        );
        $middleware = new DispatcherMiddleware();

        $response = $middleware->process($harmony->getRequest(), $harmony);

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function callNextMiddleware(): void
    {
        $harmony = $this->createHarmonyWithAction(
            static function (ServerRequestInterface $request, ResponseInterface $response) {
                return $response;
            }
        );
        $harmony->addMiddleware(new ExceptionMiddleware("next"), "exception");
        $middleware = new DispatcherMiddleware();

        $this->expectException(TestException::class);

        $middleware->process($harmony->getRequest(), $harmony);
    }

    /**
     * @test
     */
    public function exceptionWhenNoAction(): void
    {
        $middleware = new DispatcherMiddleware();

        $this->expectException(DispatcherException::class);

        $middleware->process(
            new DummyServerRequest(),
            new class implements RequestHandlerInterface {
                public function handle(ServerRequestInterface $request): ResponseInterface
                {
                    return new DummyResponse();
                }
            }
        );
    }

    /**
     * @test
     */
    public function dispatchArrayCallable(): void
    {
        $request = new DummyServerRequest();
        $request = $request->withAttribute("__action", [ExceptionController::class, "dummyAction"]);
        $middleware = new DispatcherMiddleware();

        $this->expectException(TestException::class);

        $middleware->process($request, new Harmony($request, new DummyResponse()));
    }

    /**
     * @test
     */
    public function dispatchAnonymousFunction(): void
    {
        $request = new DummyServerRequest();
        $callable = static function () {
            throw new TestException();
        };
        $request = $request->withAttribute("__action", $callable);
        $middleware = new DispatcherMiddleware();

        $this->expectException(TestException::class);

        $middleware->process($request, new Harmony($request, new DummyResponse()));
    }

    /**
     * @test
     */
    public function dispatchInvokableClass(): void
    {
        $request = new DummyServerRequest();
        $request = $request->withAttribute("__action", InvokableExceptionController::class);
        $middleware = new DispatcherMiddleware();

        $this->expectException(TestException::class);

        $middleware->process($request, new Harmony($request, new DummyResponse()));
    }

    /**
     * @test
     */
    public function getContainer(): void
    {
        $middleware = new DispatcherMiddleware(new BasicContainer());

        $container = $middleware->getContainer();

        $this->assertInstanceOf(BasicContainer::class, $container);
    }

    /**
     * @test
     */
    public function setContainer(): void
    {
        $middleware = new DispatcherMiddleware();

        $middleware->setContainer(new BasicContainer());

        $this->assertInstanceOf(BasicContainer::class, $middleware->getContainer());
    }

    /**
     * @test
     */
    public function getDefaultHandlerAttribute(): void
    {
        $middleware = new DispatcherMiddleware();

        $attributeName = $middleware->getActionAttributeName();

        $this->assertEquals("__action", $attributeName);
    }

    /**
     * @test
     */
    public function getHandlerAttribute(): void
    {
        $middleware = new DispatcherMiddleware();

        $middleware->setActionAttributeName("action");

        $this->assertEquals("action", $middleware->getActionAttributeName());
    }

    /**
     * @param mixed $callable
     */
    protected function createHarmonyWithAction($callable, string $attributeName = "__action"): Harmony
    {
        $request = new DummyServerRequest();
        $request = $request->withAttribute($attributeName, $callable);

        return new Harmony($request, new DummyResponse());
    }
}
