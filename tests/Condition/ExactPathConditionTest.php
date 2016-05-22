<?php
namespace WoohooLabsTest\Harmony\Container;

use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use WoohooLabs\Harmony\Condition\ExactPathCondition;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;

class ExactPathConditionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function evaluateToTrue()
    {
        /** @var UriInterface $uri */
        $uri = $this->getMockBuilder(UriInterface::class)->getMock();
        $uri->method("getPath")->willReturn("/api");

        /** @var ServerRequestInterface $request */
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $request->method("getUri")->willReturn($uri);

        $condition = new ExactPathCondition(["/api"]);

        $this->assertTrue($condition->evaluate($request, new DummyResponse()));
    }

    /**
     * @test
     */
    public function evaluateToFalse()
    {
        /** @var UriInterface $uri */
        $uri = $this->getMockBuilder(UriInterface::class)->getMock();
        $uri->method("getPath")->willReturn("/api");

        /** @var ServerRequestInterface $request */
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $request->method("getUri")->willReturn($uri);

        $condition = new ExactPathCondition(["/not-api"]);

        $this->assertFalse($condition->evaluate($request, new DummyResponse()));
    }
}
