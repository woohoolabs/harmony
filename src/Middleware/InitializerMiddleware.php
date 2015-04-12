<?php
namespace WoohooLabs\Harmony\Middleware;

use Interop\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use WoohooLabs\Harmony\Container\BasicContainer;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Request\FoundationRequest;
use WoohooLabs\Harmony\Request\RequestInterface;
use WoohooLabs\Harmony\Response\FoundationResponse;
use WoohooLabs\Harmony\Response\ResponseInterface;

class InitializerMiddleware implements MiddlewareInterface
{
    const ID = "initializer";

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
     * @param \Interop\Container\ContainerInterface $container
     * @param \WoohooLabs\Harmony\Request\RequestInterface $request
     * @param \WoohooLabs\Harmony\Response\ResponseInterface $response
     */
    public function __construct(ContainerInterface $container = null, RequestInterface $request = null, ResponseInterface $response = null)
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

        if ($this->request === null) {
            $this->request = new FoundationRequest(Request::createFromGlobals());
        }

        if ($this->response === null) {
            $this->response = new FoundationResponse();
        }

        $harmony->setContainer($this->container);
        $harmony->setRequest($this->request);
        $harmony->setResponse($this->response);
    }
}
