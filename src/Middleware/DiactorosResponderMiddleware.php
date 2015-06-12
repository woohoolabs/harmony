<?php
namespace WoohooLabs\Harmony\Middleware;

use WoohooLabs\Harmony\Harmony;
use Zend\Diactoros\Response\EmitterInterface;

class DiactorosResponderMiddleware implements MiddlewareInterface
{
    const ID = "diactoros_responder";

    /**
     * @var \Zend\Diactoros\Response\EmitterInterface
     */
    private $emitter;

    /**
     * @param \Zend\Diactoros\Response\EmitterInterface $emitter
     */
    public function __construct(EmitterInterface $emitter)
    {
        $this->emitter = $emitter;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return self::ID;
    }

    /**
     * @param \WoohooLabs\Harmony\Harmony $harmony
     */
    public function execute(Harmony $harmony)
    {
        $this->emitter->emit($harmony->getResponse());
    }
}
