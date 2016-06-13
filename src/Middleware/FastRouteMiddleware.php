<?php
namespace WoohooLabs\Harmony\Middleware;

use FastRoute\Dispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Exception\MethodNotAllowed;
use WoohooLabs\Harmony\Exception\RouteNotFound;

class FastRouteMiddleware
{
    /**
     * @var \FastRoute\Dispatcher
     */
    protected $fastRoute;

    /**
     * @var string
     */
    protected $actionAttributeName;

    /**
     * @param \FastRoute\Dispatcher $fastRoute
     * @param string $actionAttributeName
     */
    public function __construct(Dispatcher $fastRoute = null, $actionAttributeName = "__action")
    {
        $this->fastRoute = $fastRoute;
        $this->actionAttributeName = $actionAttributeName;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable $next
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \WoohooLabs\Harmony\Exception\MethodNotAllowed
     * @throws \WoohooLabs\Harmony\Exception\RouteNotFound
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
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

    /**
     * @return \FastRoute\Dispatcher
     */
    public function getFastRoute()
    {
        return $this->fastRoute;
    }

    /**
     * @param \FastRoute\Dispatcher $fastRoute
     */
    public function setFastRoute(Dispatcher $fastRoute)
    {
        $this->fastRoute = $fastRoute;
    }

    /**
     * @return string
     */
    public function getActionAttributeName()
    {
        return $this->actionAttributeName;
    }

    /**
     * @param string $actionAttributeName
     */
    public function setActionAttributeName($actionAttributeName)
    {
        $this->actionAttributeName = $actionAttributeName;
    }
}
