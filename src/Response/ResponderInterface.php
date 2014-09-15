<?php
namespace WoohooLabs\ApiFramework\Response;

interface ResponderInterface
{
    /**
     * @param \WoohooLabs\ApiFramework\Response\ResponseInterface $responseInfo
     */
    public function respond(ResponseInterface $responseInfo);
}
