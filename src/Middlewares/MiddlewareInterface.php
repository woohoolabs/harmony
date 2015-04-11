<?php
namespace WoohooLabs\Harmony\Middlewares;

use WoohooLabs\Harmony\Harmony;

interface MiddlewareInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @param \WoohooLabs\Harmony\Harmony $harmony
     */
    public function execute(Harmony $harmony);
}
