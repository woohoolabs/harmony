<?php
namespace WoohooLabs\ApiFramework\Routing;

use FastRoute\DataGenerator\GroupPosBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use FastRoute\Dispatcher;
use Interop\Container\ContainerInterface;
use WoohooLabs\ApiFramework\Config;
use WoohooLabs\ApiFramework\Dispatcher\ClassDispatcher;
use WoohooLabs\ApiFramework\Dispatcher\CallbackDispatcher;

class FastRouter implements RouterInterface
{
    /**
     * @var \WoohooLabs\ApiFramework\Config
     */
    protected $config;

    /**
     * @var \Interop\Container\ContainerInterface
     */
    protected $container;

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
     * @param \Interop\Container\ContainerInterface $container
     * @param \WoohooLabs\ApiFramework\Config $config
     */
    public function __construct(Config $config, ContainerInterface $container)
    {
        $this->config= $config;
        $this->container= $container;
        $this->caching= $config->isCaching();
        $this->cachePath= rtrim($config->getCacheDirectory(), "\\/")."/route.cache";
        $this->routes= new RouteCollector(new Std(), new GroupPosBased());
    }

    /**
     * @param string $verb
     * @param string $route
     * @param string $className
     * @param string $methodName
     */
    public function addRoute($verb, $route, $className, $methodName)
    {
        $this->routes->addRoute($verb, "/" . ltrim($route, "\\/"), [$className, $methodName]);
    }

    /**
     * @param string $verb
     * @param string $route
     * @param callable $handler
     */
    public function addCallbackRoute($verb, $route, \Closure $handler)
    {
        $this->routes->addRoute($verb, "/" . ltrim($route, "\\/"), $handler);
    }

    /**
     * @param string $method
     * @param string $uri
     * @return \WoohooLabs\ApiFramework\Dispatcher\AbstractDispatcher
     * @throws \WoohooLabs\ApiFramework\Routing\RouteNotFoundException
     * @throws \WoohooLabs\ApiFramework\Routing\MethodNotAllowedException
     */
    public function getDispatcher($method, $uri)
    {
        $router = \FastRoute\cachedDispatcher(
            function(RouteCollector &$r) {
                $r= $this->routes;
            }, [
                'cacheFile' => $this->cachePath,
                'cacheDisabled' => !$this->caching,
            ]);

        $routeInfo = $router->dispatch($method, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new RouteNotFoundException();
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException();
                break;
            case Dispatcher::FOUND:
                $parameters = $routeInfo[2];

                if (is_array($routeInfo[1])) {
                    $className= $routeInfo[1][0];
                    $methodName= $routeInfo[1][1];
                    return new ClassDispatcher($this->config, $this->container, $className, $methodName, $parameters);
                }

                return new CallbackDispatcher($routeInfo[1], $parameters);
        }

        throw new RouteNotFoundException();
    }
}
