<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WoohooLabs\Harmony\Container\BasicContainer;
use WoohooLabs\Harmony\Exception\DispatcherException;
use function is_array;
use function is_callable;
use function is_string;

class DispatcherMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    protected string $actionAttributeName;

    public function __construct(?ContainerInterface $container = null, string $actionAttributeName = "__action")
    {
        $this->container = $container ?? new BasicContainer();
        $this->actionAttributeName = $actionAttributeName;
    }

    /**
     * @throws DispatcherException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $action = $request->getAttribute($this->actionAttributeName);

        if ($action === null) {
            throw new DispatcherException(
                "Please set the '{$this->actionAttributeName}' attribute as a callable in the request object in " .
                "order to be able dispatch it!"
            );
        }

        $response = $handler->handle($request);

        if (is_array($action) && is_string($action[0]) && is_string($action[1])) {
            $object = $this->container->get($action[0]);
            $response = $object->{$action[1]}($request, $response);
        } else {
            if (is_callable($action) === false) {
                $action = $this->container->get($action);
            }
            $response = $action($request, $response);
        }

        return $response;
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
