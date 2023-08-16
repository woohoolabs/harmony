<?php

declare(strict_types=1);

require_once "../vendor/autoload.php";

use FastRoute\RouteCollector;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Examples\Controller\GetBookAction;
use WoohooLabs\Harmony\Examples\Controller\UserController;
use WoohooLabs\Harmony\Examples\Middleware\ExceptionHandlerMiddleware;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
use WoohooLabs\Harmony\Middleware\FastRouteMiddleware;
use WoohooLabs\Harmony\Middleware\LaminasEmitterMiddleware;

use function FastRoute\simpleDispatcher;

// Initializing the router
$router = simpleDispatcher(static function (RouteCollector $r) {
    $r->addRoute("GET", "/me", static function (ServerRequestInterface $request, ResponseInterface $response) {
        $response->getBody()->write("Hello, World!");

        return $response;
    });

    $r->addRoute("GET", "/users/{id}", [UserController::class, "getUser"]);
    $r->addRoute("GET", "/books/{id}", GetBookAction::class);
});

// Instantiating the framework
$harmony = new Harmony(ServerRequestFactory::fromGlobals(), new Response());

// Stacking up middleware
$harmony
    ->addMiddleware(new LaminasEmitterMiddleware(new SapiEmitter()))
    ->addMiddleware(new ExceptionHandlerMiddleware(new Response()))
    ->addMiddleware(new FastRouteMiddleware($router))
    ->addMiddleware(new DispatcherMiddleware())
    ->run();
