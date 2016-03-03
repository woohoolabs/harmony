<?php
namespace WoohooLabsTest\Harmony\Utils\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\MiddlewareInterface;
use WoohooLabsTest\Harmony\Utils\Exception\TestException;

class ExceptionMiddleware implements MiddlewareInterface
{
    protected $text;

    /**
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Harmony\Harmony $next
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \WoohooLabsTest\Harmony\Utils\Exception\TestException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, Harmony $next)
    {
        throw new TestException($this->text);
    }
}
