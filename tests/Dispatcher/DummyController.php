<?php
namespace WoohooLabsTest\Harmony\Dispatcher;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DummyController
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function dummyAction(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $response;
    }
}
