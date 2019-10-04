<?php

declare(strict_types=1);

namespace WoohooLabs\Harmony\Container;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class BasicContainerNotFoundException extends Exception implements NotFoundExceptionInterface
{
}
