<?php
namespace WoohooLabs\Harmony\Dispatcher;

use WoohooLabs\Harmony\Request\RequestInterface;
use WoohooLabs\Harmony\Response\ResponseInterface;

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
     * @param \WoohooLabs\Harmony\Request\RequestInterface $request
     * @param \WoohooLabs\Harmony\Response\ResponseInterface $response
     * @return \WoohooLabs\Harmony\Response\ResponseInterface
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
