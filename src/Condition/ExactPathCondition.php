<?php
namespace WoohooLabs\Harmony\Condition;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ExactPathCondition implements ConditionInterface
{
    protected $paths = [];

    /**
     * @param array $paths
     */
    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }

    public function evaluate(ServerRequestInterface $request, ResponseInterface $response)
    {
        return in_array($request->getUri()->getPath(), $this->paths) === true;
    }
}
