<?php
namespace WoohooLabs\ApiFramework\Discoverer;

use WoohooLabs\ApiFramework\Serializer\SerializerInterface;
use WoohooLabs\ApiFramework\Config;
use WoohooLabs\ApiFramework\Router\RouterInterface;

class SwaggerDiscoverer
{
    /**
     * @var string
     */
    private $swagger;

    public function __construct(
        Config $config,
        $swaggerPath,
        $mime,
        SerializerInterface $serializer,
        $caching
    ) {
        if ($config->isCaching() == false || file_exists($swaggerPath) == false) {
            $this->swagger = $serializer->deserialize(file_get_contents($swaggerPath), $mime);
        } else {
            $this->swagger = file_get_contents($swaggerPath);
        }
    }

    /**
     * @param \WoohooLabs\ApiFramework\Router\RouterInterface $router
     */
    public function addRoutes(RouterInterface $router)
    {

    }
}
