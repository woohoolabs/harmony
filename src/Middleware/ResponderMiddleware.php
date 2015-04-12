<?php
namespace WoohooLabs\Harmony\Middleware;

use WoohooLabs\Harmony\Harmony;

class ResponderMiddleware implements MiddlewareInterface
{
    const ID = "responder";

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
        $harmony->getResponse()->respond();
    }
}
