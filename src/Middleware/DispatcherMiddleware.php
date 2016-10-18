<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Middleware;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Container\BasicContainer;
use WoohooLabs\Harmony\Exception\DispatcherException;

class DispatcherMiddleware
{
    /**
     * @var \Interop\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $actionAttributeName;

    /**
     * @param \Interop\Container\ContainerInterface $container
     * @param string $actionAttributeName
     */
    public function __construct(ContainerInterface $container = null, string $actionAttributeName = "__action")
    {
        $this->container = $container === null ? new BasicContainer() : $container;
        $this->actionAttributeName = $actionAttributeName;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        $action = $request->getAttribute($this->actionAttributeName);

        if ($action === null) {
            throw new DispatcherException();
        }

        if (is_array($action) && is_string($action[0]) && is_string($action[1])) {
            $object = $this->container->get($action[0]);
            $response = call_user_func_array([$object, $action[1]], [$request, $response]);
        } else {
            if (is_callable($action) === false) {
                $action = $this->container->get($action);
            }
            $response = call_user_func_array($action, [$request, $response]);
        }

        return $next($request, $response);
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getActionAttributeName(): string
    {
        return $this->actionAttributeName;
    }

    public function setActionAttributeName(string $actionAttributeName)
    {
        $this->actionAttributeName = $actionAttributeName;
    }
}
