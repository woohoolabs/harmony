<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Utils\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FakeMiddleware
{
    protected $text;

    public function __construct(string $text = "dummy")
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        return $next();
    }
}
