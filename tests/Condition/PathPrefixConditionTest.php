<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Container;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
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
        /** @var UriInterface|MockObject $uri */
        $uri = $this->getMockBuilder(UriInterface::class)->getMock();
        $uri->method("getPath")->willReturn("/api");

        /** @var ServerRequestInterface|MockObject $request */
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $request->method("getUri")->willReturn($uri);

        $condition = new PathPrefixCondition(["/api"]);

        $this->assertTrue($condition->evaluate($request, new DummyResponse()));
    }

    /**
     * @test
     */
    public function evaluateSubPathToTrue()
    {
        /** @var UriInterface|MockObject $uri */
        $uri = $this->getMockBuilder(UriInterface::class)->getMock();
        $uri->method("getPath")->willReturn("/api/users");

        /** @var ServerRequestInterface|PHPUnit_Framework_MockObject_MockObject $request */
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $request->method("getUri")->willReturn($uri);

        $condition = new PathPrefixCondition(["/api"]);

        $this->assertTrue($condition->evaluate($request, new DummyResponse()));
    }

    /**
     * @test
     */
    public function evaluateDifferentPathToFalse()
    {
        /** @var UriInterface|PHPUnit_Framework_MockObject_MockObject $uri */
        $uri = $this->getMockBuilder(UriInterface::class)->getMock();
        $uri->method("getPath")->willReturn("/app");

        /** @var ServerRequestInterface|PHPUnit_Framework_MockObject_MockObject $request */
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $request->method("getUri")->willReturn($uri);

        $condition = new PathPrefixCondition(["/api"]);

        $this->assertFalse($condition->evaluate($request, new DummyResponse()));
    }
}
