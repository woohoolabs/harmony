<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Utils\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FakeMiddleware implements MiddlewareInterface
{
    protected string $text;

    public function __construct(string $text = "dummy")
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request);
    }
}
