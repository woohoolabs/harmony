<?php
namespace WoohooLabs\Harmony\Event;

interface EventInterface
{
    /**
     * @return \WoohooLabs\Harmony\Request\RequestInterface
     */
    public function getRequest();

    /**
     * @return \WoohooLabs\Harmony\Response\ResponseInterface
     */
    public function getResponse();
}
