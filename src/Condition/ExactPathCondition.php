<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Condition;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use function in_array;

class ExactPathCondition implements ConditionInterface
{
    /**
     * @var array
     */
    protected $paths = [];

    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }

    public function evaluate(ServerRequestInterface $request, ResponseInterface $response): bool
    {
        return in_array($request->getUri()->getPath(), $this->paths, true);
    }
}
