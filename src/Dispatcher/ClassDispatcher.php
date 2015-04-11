<?php
namespace WoohooLabs\Harmony\Dispatcher;

use Interop\Container\ContainerInterface;
use WoohooLabs\Harmony\Request\RequestInterface;
use WoohooLabs\Harmony\Response\ResponseInterface;

class ClassDispatcher extends AbstractDispatcher
{
    /**
     * @var \Interop\Container\ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
     private $className;

    /**
     * @var string
     */
    private $methodName;

    /**
     * @param \Interop\Container\ContainerInterface $container
     * @param string $className
     * @param string $methodName
     * @param array $params
     */
    public function __construct(ContainerInterface $container, $className, $methodName, array $params)
    {
        parent::__construct($params);
        $this->className = $className;
        $this->methodName= $methodName;
    }

    /**
     * @param \WoohooLabs\Harmony\Request\RequestInterface $request
     * @param \WoohooLabs\Harmony\Response\ResponseInterface $response
     * @return \WoohooLabs\Harmony\Response\ResponseInterface
     */
    public function dispatch(RequestInterface $request, ResponseInterface $response)
    {
        $object= $this->container->get($this->className);

        if(method_exists($object, "preHook") === true) {
            $object->preHook($request, $response);
        }

        $response= $object->{$this->methodName}($request, $response);

        if(method_exists($object, "postHook") === true) {
            $object->postHook($request, $response);
        }

        return $response;
    }

    /**
     * @param \Interop\Container\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container= $container;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName($className)
    {
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * @param string $methodName
     */
    public function setMethodName($methodName)
    {
        $this->methodName = $methodName;
    }
}
