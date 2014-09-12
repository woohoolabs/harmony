<?php
namespace WoohooLabs\ApiFramework\Response;

interface ResponderInterface
{
    /**
     * @param \WoohooLabs\ApiFramework\Response\ResponseInfoInterface $responseInfo
     */
    public function respond(ResponseInfoInterface $responseInfo);
}
