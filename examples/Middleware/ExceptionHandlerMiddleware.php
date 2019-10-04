<?php

declare(strict_types=1);

namespace WoohooLabs\Harmony\Examples\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;
use WoohooLabs\Harmony\Exception\MethodNotAllowed;
use WoohooLabs\Harmony\Exception\RouteNotFound;

final class ExceptionHandlerMiddleware implements MiddlewareInterface
{
    private ResponseInterface $errorResponsePrototype;

    public function __construct(ResponseInterface $errorResponsePrototype)
    {
        $this->errorResponsePrototype = $errorResponsePrototype;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (RouteNotFound | MethodNotAllowed $exception) {
            return $this->handleNotFound();
        } catch (Throwable $exception) {
            return $this->handleThrowable($exception);
        }
    }

    private function handleNotFound(): ResponseInterface
    {
        $response = $this->errorResponsePrototype->withStatus(404);

        $response->getBody()->write("<h1>Error 404</h1> <p>Page was not found!</p>");

        return $response;
    }

    private function handleThrowable(Throwable $exception): ResponseInterface
    {
        $response = $this->errorResponsePrototype->withStatus(500);

        $response->getBody()->write("<h1>Error 500</h1> <p>Error message: " . $exception->getMessage() . "</p>");

        return $response;
    }
}
