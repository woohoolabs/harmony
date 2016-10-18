<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Container;

use Exception;
use Interop\Container\ContainerInterface;

class BasicContainer implements ContainerInterface
{
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws \Interop\Container\Exception\NotFoundException  No entry was found for this identifier.
     * @throws \Interop\Container\Exception\ContainerException Error while retrieving the entry.
     *
     * @return mixed Entry
     */
    public function get($id)
    {
        if ($this->has($id) !== true) {
            throw new BasicContainerNotFoundException();
        }

        try {
            $entry = new $id();
        } catch (Exception $exception) {
            throw new BasicContainerException();
        }

        return $entry;
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        return class_exists($id);
    }
}
