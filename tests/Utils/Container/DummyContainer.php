<?php

declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Utils\Container;

use Psr\Container\ContainerInterface;

class DummyContainer implements ContainerInterface
{
    public function get(string $id): mixed
    {
        return null;
    }

    public function has(string $id): bool
    {
        return false;
    }
}
