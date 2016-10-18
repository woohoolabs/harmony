<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Condition;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PathPrefixCondition implements ConditionInterface
{
    protected $pathPrefixes = [];

    public function __construct(array $pathPrefixes)
    {
        $this->pathPrefixes = $pathPrefixes;
    }

    public function evaluate(ServerRequestInterface $request, ResponseInterface $response): bool
    {
        foreach ($this->pathPrefixes as $pathPrefix) {
            if (substr($request->getUri()->getPath(), 0, strlen($pathPrefix)) === $pathPrefix) {
                return true;
            }
        }

        return false;
    }
}
