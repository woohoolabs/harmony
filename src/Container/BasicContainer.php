<?php
namespace WoohooLabs\ApiFramework\Container;

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
            throw new ContainerNotFoundException();
        }

        try {
            $entry= new $id();
        } catch (\Exception $exception) {
            throw new ContainerException();
        }

        return $entry;
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return boolean
     */
    public function has($id)
    {
        return class_exists($id, false);
    }
}
