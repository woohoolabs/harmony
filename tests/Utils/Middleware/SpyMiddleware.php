<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Utils\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SpyMiddleware
{
    /**
     * @var bool
     */
    protected $invoked;

    public function __construct()
    {
        $this->invoked = false;
    }

    public function isInvoked(): bool
    {
        return $this->invoked;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        $this->invoked = true;

        return $next();
    }
}
