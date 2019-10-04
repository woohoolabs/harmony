<?php

declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Utils\Container;

use Psr\Container\ContainerInterface;

class DummyContainer implements ContainerInterface
{
    /**
     * @param string $id
     * @return null
     */
    public function get($id)
    {
        return null;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id)
    {
        return false;
    }
}
