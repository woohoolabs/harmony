<?php
namespace WoohooLabsTest\Harmony\Dispatcher;

use WoohooLabs\Harmony\Dispatcher\CallbackDispatcher;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyServerRequest;
use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CallbackDispatcherTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \WoohooLabs\Harmony\Dispatcher\CallbackDispatcher::dispatch()
     * @covers \WoohooLabs\Harmony\Dispatcher\CallbackDispatcher::getCallback()
     */
    public function testAnonymousFunction()
    {
        $dispatcher = new CallbackDispatcher(function (ServerRequestInterface $request, ResponseInterface $response) {
           return $response;
        });
        $response = $dispatcher->dispatch(new DummyServerRequest(), new DummyResponse());

        $this->assertInstanceOf(DummyResponse::class, $response);
        $this->assertInstanceOf(DummyResponse::class, $dispatcher->getCallback());
    }
}
