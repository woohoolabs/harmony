<?php
namespace WoohooLabsTest\Harmony\Utils\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\MiddlewareInterface;

class HeaderMiddleware implements MiddlewareInterface
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
     * @param string $name
     * @param string|string[] $value
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Harmony\Harmony $next
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, Harmony $next)
    {
        return $next($response->withHeader($this->name, $this->value));
    }
}
