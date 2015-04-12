<?php
namespace WoohooLabs\Harmony\Middleware;

use FastRoute\Dispatcher;
use WoohooLabs\Harmony\Dispatcher\CallbackDispatcher;
use WoohooLabs\Harmony\Dispatcher\ClassDispatcher;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Router\MethodNotAllowedException;
use WoohooLabs\Harmony\Router\RouteNotFoundException;

class RouterMiddleware implements MiddlewareInterface
{
    const ID = "router";

    /**
     * @var \FastRoute\Dispatcher
     */
    protected $dispatcher;

    /**
     * @param \FastRoute\Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return self::ID;
    }

    /**
     * @param \WoohooLabs\Harmony\Harmony $harmony
     * @throws \WoohooLabs\Harmony\Router\MethodNotAllowedException
     * @throws \WoohooLabs\Harmony\Router\RouteNotFoundException
     */
    public function execute(Harmony $harmony)
    {
        $routeInfo = $this->dispatcher->dispatch($harmony->getRequest()->getMethod(), $harmony->getRequest()->getUri());

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new RouteNotFoundException();
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException();
                break;
            case Dispatcher::FOUND:
                $params = $routeInfo[2];

                if (is_array($routeInfo[1])) {
                    $className= $routeInfo[1][0];
                    $methodName= $routeInfo[1][1];
                    $harmony->setDispatcher(new ClassDispatcher($harmony->getContainer(), $className, $methodName, $params));
                } else {
                    $harmony->setDispatcher(new CallbackDispatcher($routeInfo[1], $params));
                }
                break;
            default:
                throw new RouteNotFoundException();
        }
    }
}
