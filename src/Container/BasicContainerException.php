<?php

declare(strict_types=1);

namespace WoohooLabs\Harmony\Container;

use Exception;
use Psr\Container\ContainerExceptionInterface;

class BasicContainerException extends Exception implements ContainerExceptionInterface
{
}
