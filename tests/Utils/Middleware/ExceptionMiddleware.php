<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Utils\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WoohooLabs\Harmony\Tests\Utils\Exception\TestException;

class ExceptionMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    protected $text;

    public function __construct(string $text = "Exception")
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @throws TestException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        throw new TestException($this->text);
    }
}
