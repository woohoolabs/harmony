<?php
declare(strict_types=1);

namespace WoohooLabsTest\Harmony\Utils\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HeaderMiddleware
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|string[]
     */
    protected $value;

    /**
     * @param string|string[] $value
     */
    public function __construct(string $name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        return $next(null, $response->withHeader($this->name, $this->value));
    }
}
