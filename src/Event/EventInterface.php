<?php
namespace WoohooLabs\ApiFramework\Event;

interface EventInterface
{
    /**
     * @return \WoohooLabs\ApiFramework\Request\RequestInterface
     */
    public function getRequest();

    /**
     * @return \WoohooLabs\ApiFramework\Response\ResponseInterface
     */
    public function getResponse();
}
