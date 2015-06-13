<?php
namespace WoohooLabs\Harmony\Middleware;

use FastRoute\Dispatcher;
use WoohooLabs\Harmony\Dispatcher\CallbackDispatcherTest;
use WoohooLabs\Harmony\Dispatcher\ClassDispatcherInterface;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Router\MethodNotAllowedException;
use WoohooLabs\Harmony\Router\RouteNotFoundException;

class FastRouteMiddleware implements MiddlewareInterface
{
    const ID = "fast_route";

    /**
     * @var \FastRoute\Dispatcher
     */
    protected $dispatcher;

    /**
     * @param \FastRoute\Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher = null)
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
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException();
            case Dispatcher::FOUND:
                foreach ($routeInfo[2] as $param => $value) {
                    $harmony->setRequest($harmony->getRequest()->withAttribute($param, $value));
                }

                if (is_array($routeInfo[1])) {
                    $className= $routeInfo[1][0];
                    $methodName= $routeInfo[1][1];
                    $harmony->setDispatcher(
                        new ClassDispatcherInterface($harmony->getContainer(), $className, $methodName)
                    );
                } else {
                    $harmony->setDispatcher(new CallbackDispatcherTest($routeInfo[1]));
                }
                break;
            default:
                throw new RouteNotFoundException();
        }

        $harmony->next();
    }

    /**
     * @return \FastRoute\Dispatcher
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @param \FastRoute\Dispatcher $dispatcher
     */
    public function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
}
