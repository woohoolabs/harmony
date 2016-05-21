<?php
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

    /**
     * @param \Zend\Diactoros\Response\EmitterInterface $emitter
     * @param bool $checkOutputStart
     */
    public function __construct(EmitterInterface $emitter = null, $checkOutputStart = false)
    {
        $this->emitter = $emitter ? $emitter : new SapiEmitter();
        $this->checkOutputStart = $checkOutputStart;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable $next
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $response = $next();

        if (headers_sent() === false || $this->checkOutputStart === false) {
            $this->emitter->emit($response);
        }

        return $response;
    }

    /**
     * @return \Zend\Diactoros\Response\EmitterInterface
     */
    public function getEmitter()
    {
        return $this->emitter;
    }

    /**
     * @param \Zend\Diactoros\Response\EmitterInterface $emitter
     */
    public function setEmitter(EmitterInterface $emitter)
    {
        $this->emitter = $emitter;
    }

    /**
     * @return bool
     */
    public function isOutputStartChecked()
    {
        return $this->checkOutputStart;
    }

    /**
     * @param bool $checkOutputStart
     */
    public function setCheckOutputStart($checkOutputStart)
    {
        $this->checkOutputStart = $checkOutputStart;
    }
}
