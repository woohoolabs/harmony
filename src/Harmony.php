<?php

declare(strict_types=1);

namespace WoohooLabs\Harmony;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WoohooLabs\Harmony\Condition\ConditionInterface;

use function array_key_exists;

class Harmony implements RequestHandlerInterface
{
    protected ServerRequestInterface $request;
    protected ResponseInterface $response;
    /** @var array<int, array<string, mixed>> */
    protected array $middleware = [];
    protected int $currentMiddleware = -1;

    public function __construct(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function run(): ResponseInterface
    {
        return $this->handle($this->request);
    }

    /**
     * @internal
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->request = $request;
        $this->currentMiddleware++;

        // Stop if there isn't any executable middleware remaining
        if (array_key_exists($this->currentMiddleware, $this->middleware) === false) {
            return $this->response;
        }

        // Execute the next condition or middleware
        if (array_key_exists("condition", $this->middleware[$this->currentMiddleware])) {
            $this->executeCondition($this->middleware[$this->currentMiddleware]);
        } else {
            $this->executeMiddleware($this->middleware[$this->currentMiddleware]);
        }

        return $this->response;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    public function getMiddleware(string $id): ?MiddlewareInterface
    {
        $position = $this->findMiddleware($id);

        if ($position === null) {
            return null;
        }

        return $this->middleware[$position]["middleware"];
    }

    public function addMiddleware(MiddlewareInterface $middleware, ?string $id = null): Harmony
    {
        $this->middleware[] = [
            "id" => $id,
            "middleware" => $middleware,
        ];

        return $this;
    }

    public function addCondition(ConditionInterface $condition, callable $callableOnSuccess): Harmony
    {
        $this->middleware[] = [
            "condition" => $condition,
            "middleware" => $callableOnSuccess,
        ];

        return $this;
    }

    protected function findMiddleware(string $id): ?int
    {
        foreach ($this->middleware as $k => $middleware) {
            if ($middleware["id"] === $id) {
                return $k;
            }
        }

        return null;
    }

    /**
     * @param array<string, mixed> $middlewareArray
     */
    protected function executeMiddleware(array $middlewareArray): void
    {
        /** @var MiddlewareInterface $middleware */
        $middleware = $middlewareArray["middleware"];

        $this->response = $middleware->process($this->request, $this);
    }

    /**
     * @param array<string, mixed> $conditionArray
     */
    protected function executeCondition(array $conditionArray): void
    {
        /** @var ConditionInterface $condition */
        $condition = $conditionArray["condition"];
        /** @var callable $callable */
        $callable = $conditionArray["middleware"];

        if ($condition->evaluate($this->request, $this->response) === false) {
            $this->handle($this->request);

            return;
        }

        $harmony = new Harmony($this->request, $this->response);
        $callable($harmony, $this->request);
        $harmony->run();

        $this->request = $harmony->request;
        $this->response = $harmony->response;

        $this->handle($this->request);
    }
}
