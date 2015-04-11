<?php
namespace WoohooLabs\Harmony\Dispatcher;

use WoohooLabs\Harmony\Request\RequestInterface;
use WoohooLabs\Harmony\Response\ResponseInterface;

abstract class AbstractDispatcher
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * @param \WoohooLabs\Harmony\Request\RequestInterface $request
     * @param \WoohooLabs\Harmony\Response\ResponseInterface $response
     * @return \WoohooLabs\Harmony\Response\ResponseInterface
     */
    abstract public function dispatch(RequestInterface $request, ResponseInterface $response);

    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->parameters = $params;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->parameters;
    }
}
