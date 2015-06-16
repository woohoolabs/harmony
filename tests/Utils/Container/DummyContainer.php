<?php
namespace WoohooLabsTest\Harmony\Utils\Container;

use Interop\Container\ContainerInterface;

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
