<?php
namespace WoohooLabsTest\Harmony\Utils\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Harmony;

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

    /**
     * @return bool
     */
    public function isInvoked()
    {
        return $this->invoked;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Harmony\Harmony $next
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, Harmony $next)
    {
        $this->invoked = true;

        return $next();
    }
}
