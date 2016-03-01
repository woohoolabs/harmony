<?php
namespace WoohooLabsTest\Harmony\Utils\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabsTest\Harmony\Utils\Exception\TestException;

class ExceptionController
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \WoohooLabsTest\Harmony\Utils\Exception\TestException
     */
    public function dummyAction(ServerRequestInterface $request, ResponseInterface $response)
    {
        throw new TestException();
    }
}
