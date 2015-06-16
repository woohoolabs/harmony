<?php
namespace WoohooLabs\Harmony\Middleware;

use WoohooLabs\Harmony\Harmony;

class DispatcherMiddleware implements MiddlewareInterface
{
    const ID = "dispatcher";

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
        $harmony->setResponse($harmony->getDispatcher()->dispatch($harmony->getRequest(), $harmony->getResponse()));
        $harmony->next();
    }
}
