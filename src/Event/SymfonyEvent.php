<?php
namespace WoohooLabs\ApiFramework\Event;

use Symfony\Component\EventDispatcher\Event;
use WoohooLabs\ApiFramework\Request\RequestInterface;
use WoohooLabs\ApiFramework\Response\ResponseInterface;

class SymfonyEvent extends Event implements EventInterface
{
    /**
     * @var \WoohooLabs\ApiFramework\Request\RequestInterface
     */
    protected $request;

    /**
     * @var \WoohooLabs\ApiFramework\Response\ResponseInterface
     */
    protected $response;

    /**
     * @param \WoohooLabs\ApiFramework\Request\RequestInterface|null $request
     * @param \WoohooLabs\ApiFramework\Response\ResponseInterface|null $response
     */
    public function __construct(RequestInterface $request = null, ResponseInterface $response = null)
    {
        $this->request= $request;
        $this->response= $response;
    }

    /**
     * @return \WoohooLabs\ApiFramework\Request\RequestInterface|null
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return \WoohooLabs\ApiFramework\Response\ResponseInterface|null
     */
    public function getResponse()
    {
        return $this->response;
    }
}
