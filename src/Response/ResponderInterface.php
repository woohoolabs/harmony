<?php
namespace WoohooLabs\ApiFramework\Response;

interface ResponderInterface
{
    /**
     * @param \WoohooLabs\ApiFramework\Response\ResponseInterface $response
     */
    public function respond(ResponseInterface $response);
}
