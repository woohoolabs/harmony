<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Condition;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ConditionInterface
{
    public function evaluate(ServerRequestInterface $request, ResponseInterface $response): bool;
}
