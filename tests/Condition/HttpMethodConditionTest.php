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
        $request = $this->createRequestWithMethod("POST");
        $condition = new HttpMethodCondition(["POST"]);

        $result = $condition->evaluate($request, new DummyResponse());

        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function evaluateFalse()
    {
        $request = $this->createRequestWithMethod("GET");
        $condition = new HttpMethodCondition(["POST"]);

        $result = $condition->evaluate($request, new DummyResponse());

        $this->assertFalse($result);
    }

    private function createRequestWithMethod(string $method): ServerRequestInterface
    {
        /** @var ServerRequestInterface|MockObject $request */
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $request->method("getMethod")->willReturn($method);

        return $request;
    }
}
