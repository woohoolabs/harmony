<?php
namespace WoohooLabs\ApiFramework\Dispatcher;

use WoohooLabs\ApiFramework\Request\RequestInterface;

abstract class AbstractDispatcher
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * @param \WoohooLabs\ApiFramework\Request\RequestInterface $request
     * @return \WoohooLabs\ApiFramework\Response\ResponseInterface
     */
    abstract public function dispatch(RequestInterface $request);

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
