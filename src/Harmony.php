<?php
namespace WoohooLabs\Harmony;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Harmony\Dispatcher\DispatcherInterface;
use WoohooLabs\Harmony\Middleware\MiddlewareInterface;

class Harmony
{
    /**
     * @var array
     */
    protected $middlewares;

    /**
     * @var int
     */
    private $currentMiddleware;

    /**
     * @var \Interop\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @var \WoohooLabs\Harmony\Dispatcher\DispatcherInterface
     */
    protected $dispatcher;

    /**
     * @return $this
     */
    public static function build()
    {
        return new Harmony();
    }

    /**
     * Harmony constructor.
     */
    public function __construct()
    {
        $this->middlewares = [];
    }

    /**
     * Starts the framework.
     */
    public function live()
    {
        $this->currentMiddleware = 0;
        if (isset($this->middlewares[0])) {
            $this->middlewares[0]->execute($this);
        }
    }

    /**
     * Continues the execution with the next middleware.
     */
    public function next()
    {
        $this->currentMiddleware++;
        if (isset($this->middlewares[$this->currentMiddleware])) {
            $this->middlewares[$this->currentMiddleware]->execute($this);
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
     * @param string $id
     * @return \WoohooLabs\Harmony\Middleware\MiddlewareInterface|null
     */
    public function getMiddleware($id)
    {
        foreach ($this->middlewares as $k => $middleware) {
            /** @var \WoohooLabs\Harmony\Middleware\MiddlewareInterface $middleware */
            if ($middleware->getId() === $id) {
                return $this->middlewares[$k];
            }
        }

        return null;
    }

    /**
     * @param \WoohooLabs\Harmony\Middleware\MiddlewareInterface $middleware
     * @return $this
     */
    public function addMiddleware(MiddlewareInterface $middleware)
    {
        $this->middlewares[] = $middleware;

        return $this;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function removeMiddleware($id)
    {
        foreach ($this->middlewares as $k => $middleware) {
            /** @var \WoohooLabs\Harmony\Middleware\MiddlewareInterface $middleware */
            if ($middleware->getId() === $id) {
                unset($this->middlewares[$k]);
                break;
            }
        }

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
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function setRequest(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return \WoohooLabs\Harmony\Dispatcher\DispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @param \WoohooLabs\Harmony\Dispatcher\DispatcherInterface $dispatcher
     */
    public function setDispatcher(DispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
}
