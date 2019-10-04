<?php

declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Utils\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SpyMiddleware implements MiddlewareInterface
{
    protected bool $invoked;

    public function __construct()
    {
        $this->invoked = false;
    }

    public function isInvoked(): bool
    {
        return $this->invoked;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->invoked = true;

        return $handler->handle($request);
    }
}
