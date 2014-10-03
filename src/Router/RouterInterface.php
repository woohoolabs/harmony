<?php
namespace WoohooLabs\ApiFramework\Router;

interface RouterInterface
{
    /**
     * @param string $verb
     * @param string $route
     * @param string $className
     * @param string $methodName
     */
    public function addRoute($verb, $route, $className, $methodName);

    /**
     * @param string $verb
     * @param string $route
     * @param callable $handler
     */
    public function addCallbackRoute($verb, $route, \Closure $handler);

    /**
     * @param string $method
     * @param string $uri
     * @return \WoohooLabs\ApiFramework\Dispatcher\AbstractDispatcher
     */
    public function getDispatcher($method, $uri);
}
