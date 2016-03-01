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
    protected $container;

    /**
     * @var string
     */
    protected $handlerAttribute;

    /**
     * @param \Interop\Container\ContainerInterface $container
     * @param string $handlerAttribute
     */
    public function __construct(ContainerInterface $container = null, $handlerAttribute = "__callable")
    {
        $this->container = $container === null ? new BasicContainer() : $container;
        $this->handlerAttribute = $handlerAttribute;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable $next
     * @throws \Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $callable = $request->getAttribute($this->handlerAttribute);

        if ($callable === null) {
            throw new \Exception("No dispatchable callable is added to the request as an attribute!");
        }

        if (is_array($callable) && is_string($callable[0]) && is_string($callable[1])) {
            $object = $this->container->get($callable[0]);
            $response = call_user_func_array([$object, $callable[1]], [$request, $response]);
        } else {
            if (is_callable($callable) === false) {
                $callable = $this->container->get($callable);
            }
            $response = call_user_func_array($callable, [$request, $response]);
        }

        return $next($request, $response);
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param \Interop\Container\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return string
     */
    public function getHandlerAttribute()
    {
        return $this->handlerAttribute;
    }

    /**
     * @param string $handlerAttribute
     */
    public function setHandlerAttribute($handlerAttribute)
    {
        $this->handlerAttribute = $handlerAttribute;
    }
}
