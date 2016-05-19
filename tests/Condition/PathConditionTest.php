<?php
namespace WoohooLabsTest\Harmony\Container;

use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use WoohooLabs\Harmony\Condition\HttpMethodCondition;
use WoohooLabs\Harmony\Condition\PathCondition;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;

class PathConditionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function evaluateTrue()
    {
        /** @var UriInterface $uri */
        $uri = $this->getMockBuilder(UriInterface::class)->getMock();
        $uri->method("getPath")->willReturn("/api");

        /** @var ServerRequestInterface $request */
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $request->method("getUri")->willReturn($uri);

        $condition = new PathCondition(["/api"]);

        $this->assertTrue($condition->evaluate($request, new DummyResponse()));
    }

    /**
     * @test
     */
    public function evaluateFalse()
    {
        /** @var UriInterface $uri */
        $uri = $this->getMockBuilder(UriInterface::class)->getMock();
        $uri->method("getPath")->willReturn("/api");

        /** @var ServerRequestInterface $request */
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $request->method("getUri")->willReturn($uri);

        $condition = new PathCondition(["/not-api"]);

        $this->assertFalse($condition->evaluate($request, new DummyResponse()));
    }
}
