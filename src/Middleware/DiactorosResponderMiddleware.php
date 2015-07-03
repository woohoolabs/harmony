<?php
namespace WoohooLabs\Harmony\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Harmony;
use Zend\Diactoros\Response\EmitterInterface;

class DiactorosResponderMiddleware implements MiddlewareInterface
{
    /**
     * @var \Zend\Diactoros\Response\EmitterInterface
     */
    protected $emitter;

    /**
     * @param \Zend\Diactoros\Response\EmitterInterface $emitter
     */
    public function __construct(EmitterInterface $emitter = null)
    {
        $this->emitter = $emitter;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Harmony\Harmony $next
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, Harmony $next)
    {
        $this->emitter->emit($response);

        $next();
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
}
