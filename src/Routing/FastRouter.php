<?php
namespace WoohooLabs\ApiFramework\Routing;

use FastRoute\DataGenerator\GroupPosBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use FastRoute\Dispatcher;
use WoohooLabs\ApiFramework\Config;

class FastRouter implements RouterInterface
{
    /**
     * @var \FastRoute\RouteCollector
     */
    protected $routes;

    /**
     * @var string|boolean
     */
    protected $caching;

    /**
     * @var string
     */
    protected $cachePath;

    /**
     * @param \WoohooLabs\ApiFramework\Config $config
     */
    public function __construct(Config $config)
    {
        $this->caching= $config->isCaching();
        $this->cachePath= rtrim($config->getCacheDirectory(), "\\/")."/route.cache";
        $this->routes= new RouteCollector(new Std(), new GroupPosBased());
    }

    /**
     * @param string $method
     * @param string $route
     * @param string|array $handler
     */
    public function addRoute($method, $route, array $handler)
    {
        $this->routes->addRoute($method, "/" . ltrim($route, "\\/"), $handler);
    }

    /**
     * @param string $method
     * @param string $uri
     * @return \WoohooLabs\ApiFramework\Routing\HandlerInfo
     * @throws \WoohooLabs\ApiFramework\Routing\RouteNotFoundException
     * @throws \WoohooLabs\ApiFramework\Routing\MethodNotAllowedException
     */
    public function getHandlerInfo($method, $uri)
    {
        $dispatcher = \FastRoute\cachedDispatcher(
            function(RouteCollector &$r) {
                $r= $this->routes;
            }, [
                'cacheFile' => $this->cachePath,
                'cacheDisabled' => !$this->caching,
            ]);

        $routeInfo = $dispatcher->dispatch($method, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new RouteNotFoundException();
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException();
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $variables = $routeInfo[2];
                return new HandlerInfo($handler[0], $handler[1], $variables);
        }

        throw new RouteNotFoundException();
    }
}
