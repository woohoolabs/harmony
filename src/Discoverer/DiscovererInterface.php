<?php
namespace WoohooLabs\ApiFramework\Discoverer;

use WoohooLabs\ApiFramework\Router\RouterInterface;

interface DiscovererInterface
{
    /**
     * @param \WoohooLabs\ApiFramework\Router\RouterInterface $router
     */
    public function addRoutes(RouterInterface $router);
}
