<?php
namespace WoohooLabs\Harmony\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class OutputBufferResponderMiddleware
{
    /**
     * @var bool
     */
    protected $onlyClearBuffer;

    /**
     * @param bool $clearBuffer
     */
    public function __construct($clearBuffer = false)
    {
        $this->onlyClearBuffer = $clearBuffer;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable $next
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        if ($this->onlyClearBuffer === true) {
            ob_end_clean();
        } else {
            ob_end_flush();
        }

        $next();
    }
}
