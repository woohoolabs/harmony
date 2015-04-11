<?php
namespace WoohooLabs\Harmony;

use Interop\Container\ContainerInterface;
use WoohooLabs\Harmony\Dispatcher\AbstractDispatcher;
use WoohooLabs\Harmony\Middlewares\MiddlewareInterface;
use WoohooLabs\Harmony\Request\RequestInterface;
use WoohooLabs\Harmony\Response\ResponseInterface;

class Harmony
{
    /**
     * @var array
     */
    protected $middlewares;

    /**
     * @var \Interop\Container\ContainerInterface
     */
    protected $container;

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
     * @return $this
     */
    public static function build()
    {
        return new Harmony();
    }

    public function live()
    {
        foreach ($this->middlewares as $middleware) {
            /** @var \WoohooLabs\Harmony\Middlewares\MiddlewareInterface $middleware */
            $middleware->execute($this);
        }
    }

    /**
     * @return array
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }

    /**
     * @param \WoohooLabs\Harmony\Middlewares\MiddlewareInterface $middleware
     * @return $this
     */
    public function addMiddleware(MiddlewareInterface $middleware)
    {
        $this->middlewares[$middleware->getId()]= $middleware;

        return $this;
    }

    /**
     * @param string $id
     * @param \WoohooLabs\Harmony\Middlewares\MiddlewareInterface $middleware
     * @return $this
     */
    public function addMiddlewareBefore($id, MiddlewareInterface $middleware)
    {
        $this->middlewares[$id]= $middleware;

        return $this;
    }

    /**
     * @param string $id
     * @param \WoohooLabs\Harmony\Middlewares\MiddlewareInterface $middleware
     * @return $this
     */
    public function addMiddlewareAfter($id, MiddlewareInterface $middleware)
    {
        $this->middlewares[$id]= $middleware;

        return $this;
    }

    /**
     * @return \Interop\Container\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param \Interop\Container\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return \WoohooLabs\Harmony\Request\RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param \WoohooLabs\Harmony\Request\RequestInterface $request
     */
    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return \WoohooLabs\Harmony\Response\ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param \WoohooLabs\Harmony\Response\ResponseInterface $responder
     */
    public function setResponse(ResponseInterface $responder)
    {
        $this->response = $responder;
    }

    /**
     * @return Dispatcher\AbstractDispatcher
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @param Dispatcher\AbstractDispatcher $dispatcher
     */
    public function setDispatcher(AbstractDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
}
