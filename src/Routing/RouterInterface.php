<?php
namespace WoohooLabs\ApiFramework\Routing;

interface RouterInterface
{
    /**
     * @param string $method
     * @param string $route
     * @param array $handler
     */
    public function addRoute($method, $route, array $handler);

    /**
     * @param string $method
     * @param string $uri
     * @return \WoohooLabs\ApiFramework\Routing\HandlerInfo
     */
    public function getHandlerInfo($method, $uri);
}
