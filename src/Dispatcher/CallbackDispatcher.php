<?php
namespace WoohooLabs\ApiFramework\Dispatcher;

use WoohooLabs\ApiFramework\Request\RequestInterface;
use WoohooLabs\ApiFramework\Response\ResponseInterface;

class CallbackDispatcher extends AbstractDispatcher
{
     /**
     * @var callable
     */
    private $callback;

    /**
     * @param array $parameters
     * @param string $handler
     */
    public function __construct($handler, array $parameters)
    {
        parent::__construct($parameters);
        $this->callback = $handler;
    }

    /**
     * @param \WoohooLabs\ApiFramework\Request\RequestInterface $request
     * @param \WoohooLabs\ApiFramework\Response\ResponseInterface $response
     * @return \WoohooLabs\ApiFramework\Response\ResponseInterface
     */
    public function dispatch(RequestInterface $request, ResponseInterface $response)
    {
        return call_user_func($this->callback, $request, $response);
    }

    /**
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }
}
