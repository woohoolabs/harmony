<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Utils\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class InternalServerErrorMiddleware implements MiddlewareInterface
{
    private ResponseInterface $responsePrototype;

    public function __construct(ResponseInterface $responsePrototype)
    {
        $this->responsePrototype = $responsePrototype;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->responsePrototype->withStatus(500);
    }
}
