<?php
namespace WoohooLabs\ApiFramework;

use Interop\Container\ContainerInterface;
use WoohooLabs\ApiFramework\Container\BasicContainer;
use WoohooLabs\ApiFramework\Discovery\DiscovererInterface;
use WoohooLabs\ApiFramework\Request\FoundationRequest;
use WoohooLabs\ApiFramework\Request\RequestInterface;
use WoohooLabs\ApiFramework\Response\FoundationResponder;
use WoohooLabs\ApiFramework\Response\ResponderInterface;
use WoohooLabs\ApiFramework\Routing\FastRouter;
use WoohooLabs\ApiFramework\Routing\RouterInterface;
use WoohooLabs\ApiFramework\Serializer\JmsSerializer;
use WoohooLabs\ApiFramework\Serializer\SerializerInterface;

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
     * @var \WoohooLabs\ApiFramework\Discovery\DiscovererInterface
     */
    protected $discoverer;

    /**
     * @var \WoohooLabs\ApiFramework\Routing\RouterInterface
     */
    protected $router;

    /**
     * @var \WoohooLabs\ApiFramework\Serializer\SerializerInterface
     */
    protected $serializer;

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
     * @param Config $config
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
            $this->serializer = new JmsSerializer($this->config);
        }

        if ($this->responder == null) {
            $this->responder = new FoundationResponder($this->config, $this->serializer, $this->request);
        }

        if ($this->request == null) {
            $this->request = new FoundationRequest($this->config, $this->serializer);
        }
    }

    protected function discover()
    {
        if ($this->discoverer != null) {
            $this->discoverer->addRoutes($this->router);
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
        $this->response= $this->dispatcher->dispatch($this->request);
    }

    protected function respond()
    {
        $this->responder->respond($this->response);
    }

    /**
     * @param \WoohooLabs\ApiFramework\Discovery\DiscovererInterface $discoverer
     */
    public function setDiscoverer(DiscovererInterface $discoverer)
    {
        $this->discoverer = $discoverer;
    }

    /**
     * @param \WoohooLabs\ApiFramework\Routing\RouterInterface $router
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
     * @param \WoohooLabs\ApiFramework\Serializer\SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer)
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
     * @param \WoohooLabs\ApiFramework\Response\ResponderInterface $responder
     */
    public function setResponder(ResponderInterface $responder)
    {
        $this->responder = $responder;
    }
}
