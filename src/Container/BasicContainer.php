<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Container;

use Psr\Container\ContainerInterface;
use Throwable;
use function class_exists;

class BasicContainer implements ContainerInterface
{
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws BasicContainerNotFoundException  No entry was found for **this** identifier.
     * @throws BasicContainerException Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        if ($this->has($id) === false) {
            throw new BasicContainerNotFoundException();
        }

        try {
            return new $id();
        } catch (Throwable $e) {
            throw new BasicContainerException($e->getMessage());
        }
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
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
