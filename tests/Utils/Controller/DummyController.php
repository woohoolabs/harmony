<?php

declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Utils\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DummyController
{
    public function dummyAction(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $response;
    }
}
