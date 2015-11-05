<?php
namespace WoohooLabs\Harmony\Middleware;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Container\BasicContainer;

class DispatcherMiddleware
{
    /**
     * @var \Interop\Container\ContainerInterface
     */
    private $container;

    /**
     * @param \Interop\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container === null ? new BasicContainer() : $container;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable $next
     * @throws \Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $callable = $request->getAttribute("__callable");

        if ($callable === null) {
            throw new \Exception("No dispatchable callable is added to the request as an attribute!");
        }

        if (is_array($callable) && is_string($callable[0])) {
            $object = $this->container->get($callable[0]);
            $response = $object->{$callable[1]}($request, $response);
        } else {
            $response = call_user_func($callable, $request, $response);
        }

        $next($request, $response);
    }
}
