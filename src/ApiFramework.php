<?php
namespace WoohooLabs\ApiFramework;

use Interop\Container\ContainerInterface;
use WoohooLabs\ApiFramework\Container\BasicContainer;
use WoohooLabs\ApiFramework\Dispatcher\ClassDispatcher;
use WoohooLabs\ApiFramework\Request\FoundationRequest;
use WoohooLabs\ApiFramework\Request\RequestInterface;
use WoohooLabs\ApiFramework\Response\FoundationResponse;
use WoohooLabs\ApiFramework\Response\ResponseInterface;
use WoohooLabs\ApiFramework\Router\FastRouter;
use WoohooLabs\ApiFramework\Router\RouterInterface;
use WoohooLabs\ApiFramework\Serializer\Implementations\JmsSerializer;
use WoohooLabs\ApiFramework\Serializer\TwoWaySerializerInterface;

class ApiFramework
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
     * @var \WoohooLabs\ApiFramework\Router\RouterInterface
     */
    protected $router;

    /**
     * @var \WoohooLabs\ApiFramework\Serializer\TwoWaySerializerInterface
     */
    protected $serializer;

    /**
     * @var \WoohooLabs\ApiFramework\Request\RequestInterface
     */
    protected $request;

    /**
     * @var \WoohooLabs\ApiFramework\Response\ResponseInterface
     */
    protected $response;

    /**
     * @var \WoohooLabs\ApiFramework\Dispatcher\AbstractDispatcher
     */
    protected $dispatcher;

    /**
     * @var Object
     */
    protected $handler;

    /**
     * @param \WoohooLabs\ApiFramework\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function work()
    {
        $this->initialize();
        $this->discover();
        $this->route();
        $this->dispatch();
        $this->respond();
    }

    protected function initialize()
    {
        $this->initializeBaseComponents();
        $this->initializeTopComponents();
    }

    protected function initializeBaseComponents()
    {
        if ($this->container === null) {
            $this->container = new BasicContainer();
        }
    }

    protected function initializeTopComponents()
    {
        if ($this->serializer === null) {
            $this->serializer = new JmsSerializer($this->config);
        }

        if ($this->request === null) {
            $this->request = new FoundationRequest($this->config, $this->serializer);
        }

        if ($this->router === null) {
            $this->router = new FastRouter($this->config, $this->container);
        }

        if ($this->response === null) {
            $this->response = new FoundationResponse($this->serializer);
        }
    }

    /**
     * Finds all the routes.
     */
    protected function discover()
    {
        if ($this->config->getRoutes() !== null) {
            call_user_func($this->config->getRoutes(), $this->router);
        }
    }

    /**
     * Finding and instantiating the controller, saving the route parameters.
     */
    protected function route()
    {
        $this->dispatcher= $this->router->getDispatcher($this->request->getMethod(), $this->request->getUri());
        $this->request->setUriParameters($this->dispatcher->getParameters());
    }

    /**
     * Dispatching the appropriate controller method for the route.
     */
    protected function dispatch()
    {
        if ($this->dispatcher instanceof ClassDispatcher) {
            $this->dispatcher->setConfig($this->config);
            $this->dispatcher->setContainer($this->container);
        }

        $this->dispatcher->dispatch($this->request, $this->response);
    }

    protected function respond()
    {
        $this->response->respond();
    }

    /**
     * @param \WoohooLabs\ApiFramework\Router\RouterInterface $router
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param \Interop\Container\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param \WoohooLabs\ApiFramework\Serializer\TwoWaySerializerInterface $serializer
     */
    public function setSerializer(TwoWaySerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param \WoohooLabs\ApiFramework\Request\RequestInterface $request
     */
    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @param \WoohooLabs\ApiFramework\Response\ResponseInterface $responder
     */
    public function setResponse(ResponseInterface $responder)
    {
        $this->response = $responder;
    }
}
