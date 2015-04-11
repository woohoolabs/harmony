<?php
namespace WoohooLabs\Harmony\Event;

use WoohooLabs\Harmony\Request\RequestInterface;
use WoohooLabs\Harmony\Response\ResponseInterface;

interface EventDispatcherInterface
{
    public function dispatchBeforeReceivingRequest();

    /**
     * @param \WoohooLabs\Harmony\Request\RequestInterface $request
     * @param \WoohooLabs\Harmony\Response\ResponseInterface $response
     */
    public function dispatchAfterReceivingRequest(RequestInterface $request, ResponseInterface $response);

    /**
     * @param \WoohooLabs\Harmony\Request\RequestInterface $request
     * @param \WoohooLabs\Harmony\Response\ResponseInterface $response
     */
    public function dispatchAfterDiscovery(RequestInterface $request, ResponseInterface $response);

    /**
     * @param \WoohooLabs\Harmony\Request\RequestInterface $request
     * @param \WoohooLabs\Harmony\Response\ResponseInterface $response
     */
    public function dispatchAfterRouting(RequestInterface $request, ResponseInterface $response);

    /**
     * @param \WoohooLabs\Harmony\Request\RequestInterface $request
     * @param \WoohooLabs\Harmony\Response\ResponseInterface $response
     */
    public function dispatchBeforeSendingResponse(RequestInterface $request, ResponseInterface $response);
}
