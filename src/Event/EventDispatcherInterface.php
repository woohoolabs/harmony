<?php
namespace WoohooLabs\ApiFramework\Event;

use WoohooLabs\ApiFramework\Request\RequestInterface;
use WoohooLabs\ApiFramework\Response\ResponseInterface;

interface EventDispatcherInterface
{
    public function dispatchBeforeReceivingRequest();

    /**
     * @param \WoohooLabs\ApiFramework\Request\RequestInterface $request
     * @param \WoohooLabs\ApiFramework\Response\ResponseInterface $response
     */
    public function dispatchAfterReceivingRequest(RequestInterface $request, ResponseInterface $response);

    /**
     * @param \WoohooLabs\ApiFramework\Request\RequestInterface $request
     * @param \WoohooLabs\ApiFramework\Response\ResponseInterface $response
     */
    public function dispatchAfterDiscovery(RequestInterface $request, ResponseInterface $response);

    /**
     * @param \WoohooLabs\ApiFramework\Request\RequestInterface $request
     * @param \WoohooLabs\ApiFramework\Response\ResponseInterface $response
     */
    public function dispatchAfterRouting(RequestInterface $request, ResponseInterface $response);

    /**
     * @param \WoohooLabs\ApiFramework\Request\RequestInterface $request
     * @param \WoohooLabs\ApiFramework\Response\ResponseInterface $response
     */
    public function dispatchBeforeSendingResponse(RequestInterface $request, ResponseInterface $response);
}
