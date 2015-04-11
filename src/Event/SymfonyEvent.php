<?php
namespace WoohooLabs\Harmony\Event;

use Symfony\Component\EventDispatcher\Event;
use WoohooLabs\Harmony\Request\RequestInterface;
use WoohooLabs\Harmony\Response\ResponseInterface;

class SymfonyEvent extends Event implements EventInterface
{
    /**
     * @var \WoohooLabs\Harmony\Request\RequestInterface
     */
    protected $request;

    /**
     * @var \WoohooLabs\Harmony\Response\ResponseInterface
     */
    protected $response;

    /**
     * @param \WoohooLabs\Harmony\Request\RequestInterface|null $request
     * @param \WoohooLabs\Harmony\Response\ResponseInterface|null $response
     */
    public function __construct(RequestInterface $request = null, ResponseInterface $response = null)
    {
        $this->request= $request;
        $this->response= $response;
    }

    /**
     * @return \WoohooLabs\Harmony\Request\RequestInterface|null
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return \WoohooLabs\Harmony\Response\ResponseInterface|null
     */
    public function getResponse()
    {
        return $this->response;
    }
}
