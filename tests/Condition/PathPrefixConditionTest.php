<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Container;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use WoohooLabs\Harmony\Condition\PathPrefixCondition;
use WoohooLabs\Harmony\Tests\Utils\Psr7\DummyResponse;

class PathPrefixConditionTest extends TestCase
{
    /**
     * @test
     */
    public function evaluateExactPathToTrue()
    {
        $request = $this->createRequestWithPath("/api");
        $condition = new PathPrefixCondition(["/api"]);

        $result = $condition->evaluate($request, new DummyResponse());

        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function evaluateSubPathToTrue()
    {
        $request = $this->createRequestWithPath("/api/users");
        $condition = new PathPrefixCondition(["/api"]);

        $result = $condition->evaluate($request, new DummyResponse());

        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function evaluateDifferentPathToFalse()
    {
        $request = $this->createRequestWithPath("/app");
        $condition = new PathPrefixCondition(["/api"]);

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
