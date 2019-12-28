<?php

declare(strict_types=1);

namespace WoohooLabs\Harmony\Condition;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function in_array;

class HttpMethodCondition implements ConditionInterface
{
    /** @var string[] */
    protected array $methods = [];

    /**
     * @param string[] $methods
     */
    public function __construct(array $methods)
    {
        $this->methods = $methods;
    }

    public function evaluate(ServerRequestInterface $request, ResponseInterface $response): bool
    {
        return in_array($request->getMethod(), $this->methods, true);
    }
}
