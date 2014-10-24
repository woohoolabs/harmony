<?php
namespace WoohooLabs\ApiFramework\Dispatcher;

use Interop\Container\ContainerInterface;
use WoohooLabs\ApiFramework\Config;
use WoohooLabs\ApiFramework\Request\RequestInterface;
use WoohooLabs\ApiFramework\Response\ResponseInterface;

class ClassDispatcher extends AbstractDispatcher
{
    /**
     * @var \Interop\Container\ContainerInterface
     */
    private $container;

    /**
     * @var \WoohooLabs\ApiFramework\Config
     */
    private $config;

    /**
     * @var string
     */
     private $className;

    /**
     * @var string
     */
    private $methodName;

    /**
     * @param string $className
     * @param string $methodName
     * @param array $parameters
     */
    public function __construct($className, $methodName, array $parameters)
    {
        parent::__construct($parameters);
        $this->className = $className;
        $this->methodName= $methodName;
    }

    /**
     * @param \WoohooLabs\ApiFramework\Request\RequestInterface $request
     * @param \WoohooLabs\ApiFramework\Response\ResponseInterface $response
     * @return \WoohooLabs\ApiFramework\Response\ResponseInterface
     */
    public function dispatch(RequestInterface $request, ResponseInterface $response)
    {
        $object= $this->container->get($this->className);

        if($this->config->getPreHandlerHookName() !== null && method_exists($object, $this->config->getPreHandlerHookName()) === true) {
            $object->{$this->config->getPreHandlerHookName($request, $response)};
        }

        $response= $object->{$this->methodName}($request, $response);

        if($this->config->getPostHandlerHookName() !== null && method_exists($object, $this->config->getPostHandlerHookName()) === true) {
            $object->{$this->config->getPostHandlerHookName($request, $response)};
        }

        return $response;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * @param \WoohooLabs\ApiFramework\Config $config
     */
    public function setConfig(Config $config)
    {
        $this->config= $config;
    }

    /**
     * @param \Interop\Container\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container= $container;
    }
}
