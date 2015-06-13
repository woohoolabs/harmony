<?php
namespace WoohooLabs\Harmony\Dispatcher;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class ClassDispatcher implements DispatcherInterface
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
     */
    public function __construct(ContainerInterface $container, $className, $methodName)
    {
        $this->container = $container;
        $this->className = $className;
        $this->methodName= $methodName;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function dispatch(ServerRequestInterface $request, ResponseInterface $response)
    {
        $object= $this->container->get($this->className);

        if (method_exists($object, "preHook") === true) {
            $object->preHook($request, $response);
        }

        $response= $object->{$this->methodName}($request, $response);

        if (method_exists($object, "postHook") === true) {
            $object->postHook($request, $response);
        }

        return $response;
    }

    /**
     * @return \Interop\Container\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
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
}
