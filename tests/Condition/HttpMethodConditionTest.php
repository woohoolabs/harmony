<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Container;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Condition\HttpMethodCondition;
use WoohooLabs\Harmony\Tests\Utils\Psr7\DummyResponse;

class HttpMethodConditionTest extends TestCase
{
    /**
     * @test
     */
    public function evaluateTrue()
    {
        /** @var ServerRequestInterface|MockObject $request */
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
        /** @var ServerRequestInterface|MockObject $request */
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $request->method("getMethod")->willReturn("GET");

        $condition = new HttpMethodCondition(["POST"]);

        $this->assertFalse($condition->evaluate($request, new DummyResponse()));
    }
}
