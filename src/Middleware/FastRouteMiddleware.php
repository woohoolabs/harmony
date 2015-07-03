<?php
namespace WoohooLabs\Harmony\Middleware;

use FastRoute\Dispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Exception\MethodNotAllowedException;
use WoohooLabs\Harmony\Exception\RouteNotFoundException;
use WoohooLabs\Harmony\Harmony;

class FastRouteMiddleware implements MiddlewareInterface
{
    /**
     * @var \FastRoute\Dispatcher
     */
    protected $fastRoute;

    /**
     * @param \FastRoute\Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher = null)
    {
        $this->fastRoute = $dispatcher;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Harmony\Harmony $next
     * @throws \WoohooLabs\Harmony\Exception\MethodNotAllowedException
     * @throws \WoohooLabs\Harmony\Exception\RouteNotFoundException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, Harmony $next)
    {
        $routeInfo = $this->fastRoute->dispatch($request->getMethod(), $request->getUri()->getPath());

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new RouteNotFoundException();
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException();
            case Dispatcher::FOUND:
                foreach ($routeInfo[2] as $param => $value) {
                    $request = $request->withAttribute($param, $value);
                }

                $request = $request->withAttribute("__callable", $routeInfo[1]);
                break;
        }

        $next($request, $response);
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
}
