<?php
namespace WoohooLabs\ApiFramework\Dispatcher;

use WoohooLabs\ApiFramework\Request\RequestInterface;
use WoohooLabs\ApiFramework\Response\ResponseInterface;

abstract class AbstractDispatcher
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * @param \WoohooLabs\ApiFramework\Request\RequestInterface $request
     * @param \WoohooLabs\ApiFramework\Response\ResponseInterface $response
     * @return \WoohooLabs\ApiFramework\Response\ResponseInterface
     */
    abstract public function dispatch(RequestInterface $request, ResponseInterface $response);

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
