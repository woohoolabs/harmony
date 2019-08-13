<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\HttpHandlerRunner\Emitter\EmitterInterface;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use function headers_sent;

class HttpHandlerRunnerMiddleware implements MiddlewareInterface
{
    protected EmitterInterface $emitter;

    protected bool $checkOutputStart;

    public function __construct(?EmitterInterface $emitter = null, bool $checkOutputStart = false)
    {
        $this->emitter = $emitter ?? new SapiEmitter();
        $this->checkOutputStart = $checkOutputStart;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        if ($this->checkOutputStart === false || headers_sent() === false) {
            $this->emitter->emit($response);
        }

        return $response;
    }

    public function getEmitter(): EmitterInterface
    {
        return $this->emitter;
    }

    public function setEmitter(EmitterInterface $emitter): void
    {
        $this->emitter = $emitter;
    }

    public function isOutputStartChecked(): bool
    {
        return $this->checkOutputStart;
    }

    public function setCheckOutputStart(bool $checkOutputStart): void
    {
        $this->checkOutputStart = $checkOutputStart;
    }
}
