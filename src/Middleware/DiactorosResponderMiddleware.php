<?php
namespace WoohooLabs\Harmony\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmitterInterface;

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
        $this->emitter = $emitter;
        $this->checkOutputStart = $checkOutputStart;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable $next
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        if (headers_sent() === false || $this->checkOutputStart === false) {
            $this->emitter->emit($response);
        }

        return $next();
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
    public function setEmitter($emitter)
    {
        $this->emitter = $emitter;
    }

    /**
     * @return bool
     */
    public function getCheckOutputStart()
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
