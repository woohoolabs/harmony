<?php
namespace WoohooLabs\Harmony\Condition;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HttpMethodCondition implements ConditionInterface
{
    protected $methods = [];

    /**
     * @param array $methods
     */
    public function __construct(array $methods)
    {
        $this->methods = $methods;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return bool
     */
    public function evaluate(ServerRequestInterface $request, ResponseInterface $response)
    {
        return in_array($request->getMethod(), $this->methods) === true;
    }
}
