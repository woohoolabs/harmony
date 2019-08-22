<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Middleware;

use FastRoute\Dispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WoohooLabs\Harmony\Exception\FastRouteException;
use WoohooLabs\Harmony\Exception\MethodNotAllowed;
use WoohooLabs\Harmony\Exception\RouteNotFound;

class FastRouteMiddleware implements MiddlewareInterface
{
    /** @var Dispatcher|null */
    protected $fastRoute;

    protected string $actionAttributeName;

    public function __construct(?Dispatcher $fastRoute = null, string $actionAttributeName = "__action")
    {
        $this->fastRoute = $fastRoute;
        $this->actionAttributeName = $actionAttributeName;
    }

    /**
     * @throws MethodNotAllowed
     * @throws RouteNotFound
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $this->routeRequest($request);

        return $handler->handle($request);
    }

    public function getFastRoute(): Dispatcher
    {
        if ($this->fastRoute === null) {
            throw $this->createFastRouteException();
        }

        return $this->fastRoute;
    }

    public function setFastRoute(Dispatcher $fastRoute): void
    {
        $this->fastRoute = $fastRoute;
    }

    public function getActionAttributeName(): string
    {
        return $this->actionAttributeName;
    }

    public function setActionAttributeName(string $actionAttributeName): void
    {
        $this->actionAttributeName = $actionAttributeName;
    }

    protected function routeRequest(ServerRequestInterface $request): ServerRequestInterface
    {
        if ($this->fastRoute === null) {
            throw $this->createFastRouteException();
        }

        $route = $this->fastRoute->dispatch($request->getMethod(), $request->getUri()->getPath());

        if ($route[0] === Dispatcher::NOT_FOUND) {
            throw new RouteNotFound($request->getUri()->getPath());
        }

        if ($route[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            throw new MethodNotAllowed($request->getMethod());
        }

        foreach ($route[2] as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }
        $request = $request->withAttribute($this->actionAttributeName, $route[1]);

        return $request;
    }

    private function createFastRouteException(): FastRouteException
    {
        return new FastRouteException("Property FastRouteMiddleware::\$fastRoute isn't set!");
    }
}
