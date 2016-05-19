<?php
namespace WoohooLabsTest\Harmony\Utils\Condition;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Condition\ConditionInterface;

class StubCondition implements ConditionInterface
{
    /**
     * @var bool
     */
    protected $result;

    /**
     * @param bool $result
     */
    public function __construct($result)
    {
        $this->result = $result;
    }

    public function evaluate(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $this->result;
    }
}
