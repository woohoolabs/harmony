<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Container\BasicContainer;
use WoohooLabs\Harmony\Exception\DispatcherException;

class DispatcherMiddleware
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $actionAttributeName;

    public function __construct(ContainerInterface $container = null, string $actionAttributeName = "__action")
    {
        $this->container = $container === null ? new BasicContainer() : $container;
        $this->actionAttributeName = $actionAttributeName;
    }

    /**
     * @throws DispatcherException
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        $action = $request->getAttribute($this->actionAttributeName);

        if ($action === null) {
            throw new DispatcherException(
                "Please provide the '{$this->actionAttributeName}' attribute as a callable for the request object in " .
                "order to be able dispatch it!"
            );
        }

        if (is_array($action) && is_string($action[0]) && is_string($action[1])) {
            $object = $this->container->get($action[0]);
            $response = $object->{$action[1]}($request, $response);
        } else {
            if (is_callable($action) === false) {
                $action = $this->container->get($action);
            }
            $response = $action($request, $response);
        }

        return $next($request, $response);
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
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
