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
     * @param array $params
     * @param callable $callback
     */
    public function __construct(callable $callback, array $params)
    {
        parent::__construct($params);
        $this->callback = $callback;
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
