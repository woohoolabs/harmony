<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Middleware;

use FastRoute\Dispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Exception\MethodNotAllowed;
use WoohooLabs\Harmony\Exception\RouteNotFound;

class FastRouteMiddleware
{
    /**
     * @var Dispatcher
     */
    protected $fastRoute;

    /**
     * @var string
     */
    protected $actionAttributeName;

    public function __construct(Dispatcher $fastRoute = null, string $actionAttributeName = "__action")
    {
        $this->fastRoute = $fastRoute;
        $this->actionAttributeName = $actionAttributeName;
    }

    /**
     * @throws MethodNotAllowed
     * @throws RouteNotFound
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
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

        return $next($request, $response);
    }

    public function getFastRoute(): Dispatcher
    {
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
}
