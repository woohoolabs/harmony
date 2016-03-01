<?php
namespace WoohooLabsTest\Harmony\Utils\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\MiddlewareInterface;

class ReturningMiddleware implements MiddlewareInterface
{
    protected $returnValue;

    /**
     * @param mixed $returnValue
     */
    public function __construct($returnValue)
    {
        $this->returnValue = $returnValue;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Harmony\Harmony $next
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, Harmony $next)
    {
        return $this->returnValue;
    }
}
