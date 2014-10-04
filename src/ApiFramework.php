<?php
namespace WoohooLabs\ApiFramework;

use Interop\Container\ContainerInterface;
use WoohooLabs\ApiFramework\Container\BasicContainer;
use WoohooLabs\ApiFramework\Discoverer\DiscovererInterface;
use WoohooLabs\ApiFramework\Dispatcher\ClassDispatcher;
use WoohooLabs\ApiFramework\Request\FoundationRequest;
use WoohooLabs\ApiFramework\Request\RequestInterface;
use WoohooLabs\ApiFramework\Response\FoundationResponder;
use WoohooLabs\ApiFramework\Response\ResponderInterface;
use WoohooLabs\ApiFramework\Router\FastRouter;
use WoohooLabs\ApiFramework\Router\RouterInterface;
use WoohooLabs\ApiFramework\Serializer\Serializer\PhpSerializer;
use WoohooLabs\ApiFramework\Serializer\Serializer\SerializerInterface;
use WoohooLabs\ApiFramework\Serializer\Deserializer\PhpDeserializer;
use WoohooLabs\ApiFramework\Serializer\Deserializer\DeserializerInterface;

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
     * @var \WoohooLabs\ApiFramework\Discoverer\DiscovererInterface
     */
    protected $discoverer;

    /**
     * @var \WoohooLabs\ApiFramework\Router\RouterInterface
     */
    protected $router;

    /**
     * @var \WoohooLabs\ApiFramework\Serializer\Serializer\SerializerInterface
     */
    protected $serializer;

    /**
     * @var \WoohooLabs\ApiFramework\Serializer\Deserializer\DeserializerInterface
     */
    protected $deserializer;

    /**
     * @var \WoohooLabs\ApiFramework\Response\ResponderInterface
     */
    protected $responder;

    /**
     * @var \WoohooLabs\ApiFramework\Request\RequestInterface
     */
    protected $request;

    /**
     * @var \WoohooLabs\ApiFramework\Dispatcher\AbstractDispatcher
     */
    protected $dispatcher;

    /**
     * @var Object
     */
    protected $handler;

    /**
     * @var \WoohooLabs\ApiFramework\Response\ResponseInterface
     */
    protected $response;

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
        if ($this->container == null) {
            $this->container = new BasicContainer();
        }

        if ($this->router == null) {
            $this->router = new FastRouter($this->config, $this->container);
        }

        if ($this->serializer == null) {
            $this->serializer = new PhpSerializer();
        }

        if ($this->deserializer == null) {
            $this->deserializer = new PhpDeserializer();
        }

        if ($this->responder == null) {
            $this->responder = new FoundationResponder($this->config, $this->serializer, $this->request);
        }

        if ($this->request == null) {
            $this->request = new FoundationRequest($this->config, $this->deserializer);
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
        $this->response= $this->dispatcher->dispatch($this->request);
    }

    protected function respond()
    {
        $this->responder->respond($this->response);
    }

    /**
     * @param \WoohooLabs\ApiFramework\Discoverer\DiscovererInterface $discoverer
     */
    public function setDiscoverer(DiscovererInterface $discoverer)
    {
        $this->discoverer = $discoverer;
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
     * @param \WoohooLabs\ApiFramework\Serializer\Serializer\SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param \WoohooLabs\ApiFramework\Serializer\Deserializer\DeserializerInterface $deserializer
     */
    public function setDeserializer(DeserializerInterface $deserializer)
    {
        $this->deserializer = $deserializer;
    }

    /**
     * @param \WoohooLabs\ApiFramework\Request\RequestInterface $request
     */
    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @param \WoohooLabs\ApiFramework\Response\ResponderInterface $responder
     */
    public function setResponder(ResponderInterface $responder)
    {
        $this->responder = $responder;
    }
}
