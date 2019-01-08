<?php
declare(strict_types=1);

require_once "../vendor/autoload.php";

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Examples\Controller\GetBookAction;
use WoohooLabs\Harmony\Examples\Controller\UserController;
use WoohooLabs\Harmony\Examples\Middleware\ExceptionHandlerMiddleware;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
use WoohooLabs\Harmony\Middleware\FastRouteMiddleware;
use WoohooLabs\Harmony\Middleware\HttpHandlerRunnerMiddleware;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

// Initializing the router
$router = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute("GET", "/me", function (ServerRequestInterface $request, ResponseInterface $response) {
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
    ->addMiddleware(new HttpHandlerRunnerMiddleware(new SapiEmitter()))
    ->addMiddleware(new ExceptionHandlerMiddleware(new Response()))
    ->addMiddleware(new FastRouteMiddleware($router))
    ->addMiddleware(new DispatcherMiddleware());

// Run!
$harmony();
