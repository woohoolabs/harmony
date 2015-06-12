<?php
namespace WoohooLabs\Harmony\Middleware;

use Interop\Container\ContainerInterface;
use WoohooLabs\Harmony\Container\BasicContainer;
use WoohooLabs\Harmony\Harmony;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class InitializerMiddleware implements MiddlewareInterface
{
    const ID = "initializer";

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
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Interop\Container\ContainerInterface $container
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $response, ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return self::ID;
    }

    /**
     * @param \WoohooLabs\Harmony\Harmony $harmony
     */
    public function execute(Harmony $harmony)
    {
        if ($this->container === null) {
            $this->container = new BasicContainer();
        }

        $harmony->setContainer($this->container);
        $harmony->setRequest($this->request);
        $harmony->setResponse($this->response);

        $harmony->next();
    }
}
