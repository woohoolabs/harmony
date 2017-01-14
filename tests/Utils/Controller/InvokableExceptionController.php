<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Utils\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Tests\Utils\Exception\TestException;

class InvokableExceptionController
{
    /**
     * @throws \WoohooLabs\Harmony\Tests\Utils\Exception\TestException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        throw new TestException();
    }
}
