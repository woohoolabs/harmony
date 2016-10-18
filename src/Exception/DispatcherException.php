<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Exception;

use Exception;

class DispatcherException extends Exception
{
    public function __construct()
    {
        parent::__construct("There is nothing to dispatch!");
    }
}
