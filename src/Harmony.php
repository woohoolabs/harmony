<?php
namespace WoohooLabs\Harmony;

use Interop\Container\ContainerInterface;
use WoohooLabs\Harmony\Container\BasicContainer;
use WoohooLabs\Harmony\Dispatcher\ClassDispatcher;
use WoohooLabs\Harmony\Request\FoundationRequest;
use WoohooLabs\Harmony\Request\RequestInterface;
use WoohooLabs\Harmony\Response\FoundationResponse;
use WoohooLabs\Harmony\Response\ResponseInterface;
use WoohooLabs\Harmony\Router\FastRouter;
use WoohooLabs\Harmony\Router\RouterInterface;
use WoohooLabs\Harmony\Serializer\Implementations\JmsSerializer;
use WoohooLabs\Harmony\Serializer\TwoWaySerializerInterface;

class Harmony
{
    /**
     * @var \WoohooLabs\Harmony\Config
     */
    protected $config;

    /**
     * @var \Interop\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var \WoohooLabs\Harmony\Router\RouterInterface
     */
    protected $router;

    /**
     * @var \WoohooLabs\Harmony\Serializer\TwoWaySerializerInterface
     */
    protected $serializer;

    /**
     * @var \WoohooLabs\Harmony\Request\RequestInterface
     */
    protected $request;

    /**
     * @var \WoohooLabs\Harmony\Response\ResponseInterface
     */
    protected $response;

    /**
     * @var \WoohooLabs\Harmony\Dispatcher\AbstractDispatcher
     */
    protected $dispatcher;

    /**
     * @var Object
     */
    protected $handler;

    /**
     * @param \WoohooLabs\Harmony\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function live()
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
     * @param \WoohooLabs\Harmony\Router\RouterInterface $router
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
     * @param \WoohooLabs\Harmony\Serializer\TwoWaySerializerInterface $serializer
     */
    public function setSerializer(TwoWaySerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param \WoohooLabs\Harmony\Request\RequestInterface $request
     */
    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @param \WoohooLabs\Harmony\Response\ResponseInterface $responder
     */
    public function setResponse(ResponseInterface $responder)
    {
        $this->response = $responder;
    }
}
