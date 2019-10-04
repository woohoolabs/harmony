<?php

declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Condition;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use WoohooLabs\Harmony\Condition\ExactPathCondition;
use WoohooLabs\Harmony\Tests\Utils\Psr7\DummyResponse;

class ExactPathConditionTest extends TestCase
{
    /**
     * @test
     */
    public function evaluateToTrue(): void
    {
        $request = $this->createRequestWithPath("/api");
        $condition = new ExactPathCondition(["/api"]);

        $result = $condition->evaluate($request, new DummyResponse());

        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function evaluateToFalse(): void
    {
        $request = $this->createRequestWithPath("/api");
        $condition = new ExactPathCondition(["/not-api"]);

        $result = $condition->evaluate($request, new DummyResponse());

        $this->assertFalse($result);
    }

    private function createRequestWithPath(string $path): ServerRequestInterface
    {
        /** @var UriInterface|MockObject $uri */
        $uri = $this->getMockBuilder(UriInterface::class)->getMock();
        $uri->method("getPath")->willReturn($path);

        /** @var ServerRequestInterface|MockObject $request */
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $request->method("getUri")->willReturn($uri);

        return $request;
    }
}
