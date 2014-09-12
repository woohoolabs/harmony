<?php
namespace WoohooLabs\ApiFramework\Discovery;

use WoohooLabs\ApiFramework\Routing\RouterInterface;

interface DiscovererInterface
{
    /**
     * @param \WoohooLabs\ApiFramework\Routing\RouterInterface $router
     */
    public function addRoutes(RouterInterface $router);
}
