<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Utils\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Tests\Utils\Exception\TestException;

class ExceptionController
{
    /**
     * @throws \WoohooLabs\Harmony\Tests\Utils\Exception\TestException
     */
    public function dummyAction(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        throw new TestException();
    }
}
