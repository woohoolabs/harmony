<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Condition;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HttpMethodCondition implements ConditionInterface
{
    /**
     * @var array
     */
    protected $methods = [];

    public function __construct(array $methods)
    {
        $this->methods = $methods;
    }

    public function evaluate(ServerRequestInterface $request, ResponseInterface $response): bool
    {
        return in_array($request->getMethod(), $this->methods, true);
    }
}
