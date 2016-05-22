<?php
namespace WoohooLabsTest\Harmony\Container;

use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use WoohooLabs\Harmony\Condition\PathPrefixCondition;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;

class PathPrefixConditionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function evaluateExactPathToTrue()
    {
        /** @var UriInterface $uri */
        $uri = $this->getMockBuilder(UriInterface::class)->getMock();
        $uri->method("getPath")->willReturn("/api");

        /** @var ServerRequestInterface $request */
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
        /** @var UriInterface $uri */
        $uri = $this->getMockBuilder(UriInterface::class)->getMock();
        $uri->method("getPath")->willReturn("/api/users");

        /** @var ServerRequestInterface $request */
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
        /** @var UriInterface $uri */
        $uri = $this->getMockBuilder(UriInterface::class)->getMock();
        $uri->method("getPath")->willReturn("/app");

        /** @var ServerRequestInterface $request */
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $request->method("getUri")->willReturn($uri);

        $condition = new PathPrefixCondition(["/api"]);

        $this->assertFalse($condition->evaluate($request, new DummyResponse()));
    }
}
