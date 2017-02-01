<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmitterInterface;
use Zend\Diactoros\Response\SapiEmitter;

class DiactorosResponderMiddleware
{
    /**
     * @var \Zend\Diactoros\Response\EmitterInterface
     */
    protected $emitter;

    /**
     * @var bool
     */
    protected $checkOutputStart;

    public function __construct(EmitterInterface $emitter = null, bool $checkOutputStart = false)
    {
        $this->emitter = $emitter ? $emitter : new SapiEmitter();
        $this->checkOutputStart = $checkOutputStart;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        $response = $next($request, $response);

        if ($this->checkOutputStart === false || headers_sent() === false) {
            $this->emitter->emit($response);
        }

        return $response;
    }

    public function getEmitter(): EmitterInterface
    {
        return $this->emitter;
    }

    public function setEmitter(EmitterInterface $emitter)
    {
        $this->emitter = $emitter;
    }

    public function isOutputStartChecked(): bool
    {
        return $this->checkOutputStart;
    }

    public function setCheckOutputStart(bool $checkOutputStart)
    {
        $this->checkOutputStart = $checkOutputStart;
    }
}
