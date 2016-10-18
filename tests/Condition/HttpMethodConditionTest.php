<?php
declare(strict_types=1);

namespace WoohooLabsTest\Harmony\Container;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Condition\HttpMethodCondition;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;

class HttpMethodConditionTest extends TestCase
{
    /**
     * @test
     */
    public function evaluateTrue()
    {
        /** @var ServerRequestInterface $request */
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $request->method("getMethod")->willReturn("POST");

        $condition = new HttpMethodCondition(["POST"]);

        $this->assertTrue($condition->evaluate($request, new DummyResponse()));
    }

    /**
     * @test
     */
    public function evaluateFalse()
    {
        /** @var ServerRequestInterface $request */
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $request->method("getMethod")->willReturn("GET");

        $condition = new HttpMethodCondition(["POST"]);

        $this->assertFalse($condition->evaluate($request, new DummyResponse()));
    }
}
