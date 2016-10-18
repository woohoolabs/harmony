<?php
declare(strict_types=1);

namespace WoohooLabsTest\Harmony\Utils\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ReturningMiddleware
{
    protected $returnValue;

    /**
     * @param mixed $returnValue
     */
    public function __construct($returnValue)
    {
        $this->returnValue = $returnValue;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        return $this->returnValue;
    }
}
