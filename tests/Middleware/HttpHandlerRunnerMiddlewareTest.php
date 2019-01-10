<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Middleware;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\HttpHandlerRunnerMiddleware;
use WoohooLabs\Harmony\Tests\Utils\Emitter\DummyHttpHandlerRunnerEmitter;
use WoohooLabs\Harmony\Tests\Utils\Psr7\DummyResponse;
use WoohooLabs\Harmony\Tests\Utils\Psr7\DummyServerRequest;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

class HttpHandlerRunnerMiddlewareTest extends TestCase
{
    /**
     * @test
     */
    public function constructWithCustomEmitter()
    {
        $emitter = new DummyHttpHandlerRunnerEmitter();
        $middleware = new HttpHandlerRunnerMiddleware($emitter);

        $returnedEmitter = $middleware->getEmitter();

        $this->assertEquals($emitter, $returnedEmitter);
    }

    /**
     * @test
     */
    public function constructWithDefaultEmitter()
    {
        $middleware = new HttpHandlerRunnerMiddleware();

        $returnedEmitter = $middleware->getEmitter();

        $this->assertInstanceOf(SapiEmitter::class, $returnedEmitter);
    }

    /**
     * @test
     */
    public function setEmitter()
    {
        $middleware = new HttpHandlerRunnerMiddleware();

        $middleware->setEmitter(new DummyHttpHandlerRunnerEmitter());

        $this->assertInstanceOf(DummyHttpHandlerRunnerEmitter::class, $middleware->getEmitter());
    }

    /**
     * @test
     */
    public function process()
    {
        $harmony = $this->createHarmony();
        $middleware = new HttpHandlerRunnerMiddleware(new DummyHttpHandlerRunnerEmitter());

        $this->expectOutputString("true");

        $middleware->process($harmony->getRequest(), $harmony);
    }

    /**
     * @test
     */
    public function isOutputStartChecked()
    {
        $middleware = new HttpHandlerRunnerMiddleware(null, true);

        $isOutputStarted = $middleware->isOutputStartChecked();

        $this->assertTrue($isOutputStarted);
    }

    /**
     * @test
     */
    public function setCheckOutputStart()
    {
        $middleware = new HttpHandlerRunnerMiddleware(null, true);

        $middleware->setCheckOutputStart(false);

        $this->assertFalse($middleware->isOutputStartChecked());
    }

    private function createHarmony(): Harmony
    {
        return new Harmony(new DummyServerRequest(), new DummyResponse());
    }
}
