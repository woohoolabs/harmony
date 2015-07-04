<?php
namespace WoohooLabsTest\Harmony\Dispatcher;

use WoohooLabsTest\Harmony\Utils\Psr7\DummyResponse;
use WoohooLabsTest\Harmony\Utils\Psr7\DummyServerRequest;
use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CallbackDispatcher extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \WoohooLabs\Harmony\Dispatcher\CallbackDispatcher::__construct()
     * @covers \WoohooLabs\Harmony\Dispatcher\CallbackDispatcher::dispatch()
     * @covers \WoohooLabs\Harmony\Dispatcher\CallbackDispatcher::getCallback()
     */
    public function AnonymousFunction()
    {
        $originalRequest = new DummyServerRequest();
        $originalResponse = new DummyResponse();
        $dispatcher = new CallbackDispatcher(
            function (ServerRequestInterface $request, ResponseInterface $response) {
                return $response;
            }
        );

        $response = $dispatcher->dispatch($originalRequest, $originalResponse);
        $callbackResponse = call_user_func($dispatcher->getCallback(), new DummyServerRequest(), new DummyResponse());

        $this->assertInstanceOf(DummyResponse::class, $response);
        $this->assertEquals($response, $callbackResponse);
    }
}
