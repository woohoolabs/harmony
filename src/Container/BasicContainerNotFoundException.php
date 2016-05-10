<?php
namespace WoohooLabs\Harmony\Container;

use Exception;
use Interop\Container\Exception\NotFoundException;

class BasicContainerNotFoundException extends Exception implements NotFoundException
{
}
