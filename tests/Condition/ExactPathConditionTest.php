<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Container;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use WoohooLabs\Harmony\Condition\ExactPathCondition;
use WoohooLabs\Harmony\Tests\Utils\Psr7\DummyResponse;

class ExactPathConditionTest extends TestCase
{
    /**
     * @test
     */
    public function evaluateToTrue()
    {
        /** @var UriInterface|PHPUnit_Framework_MockObject_MockObject $uri */
        $uri = $this->getMockBuilder(UriInterface::class)->getMock();
        $uri->method("getPath")->willReturn("/api");

        /** @var ServerRequestInterface|PHPUnit_Framework_MockObject_MockObject $request */
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
        /** @var UriInterface|PHPUnit_Framework_MockObject_MockObject $uri */
        $uri = $this->getMockBuilder(UriInterface::class)->getMock();
        $uri->method("getPath")->willReturn("/api");

        /** @var ServerRequestInterface|PHPUnit_Framework_MockObject_MockObject $request */
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $request->method("getUri")->willReturn($uri);

        $condition = new ExactPathCondition(["/not-api"]);

        $this->assertFalse($condition->evaluate($request, new DummyResponse()));
    }
}
