<?php
declare(strict_types=1);

namespace WoohooLabsTest\Harmony\Utils\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabsTest\Harmony\Utils\Exception\TestException;

class InvokableExceptionController
{
    /**
     * @throws \WoohooLabsTest\Harmony\Utils\Exception\TestException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        throw new TestException();
    }
}
