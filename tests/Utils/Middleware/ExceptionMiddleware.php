<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Utils\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Tests\Utils\Exception\TestException;

class ExceptionMiddleware
{
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
     * @throws \WoohooLabs\Harmony\Tests\Utils\Exception\TestException
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        throw new TestException($this->text);
    }
}
