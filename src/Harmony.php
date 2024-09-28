<?php

declare(strict_types=1);

namespace WoohooLabs\Harmony;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WoohooLabs\Harmony\Condition\ConditionInterface;

use function array_key_exists;
use function assert;
use function count;

class Harmony implements RequestHandlerInterface
{
    protected ServerRequestInterface $request;
    protected ResponseInterface $response;
    /** @var array<int, array{id: string|null, condition?: ConditionInterface, callback?: callable, middleware?: MiddlewareInterface}> */
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

        return $this->middleware[$position]["middleware"] ?? null;
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
            "id" => null,
            "condition" => $condition,
            "callback" => $callableOnSuccess,
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
     * @param array{id: string|null, condition?: ConditionInterface, callback?: callable, middleware?: MiddlewareInterface} $middlewareArray
     */
    protected function executeMiddleware(array $middlewareArray): void
    {
        $middleware = $middlewareArray["middleware"] ?? null;
        assert($middleware !== null);

        $this->response = $middleware->process($this->request, $this);
    }

    /**
     * @param array{id: string|null, condition: ConditionInterface, callback?: callable, middleware?: MiddlewareInterface} $conditionArray
     */
    protected function executeCondition(array $conditionArray): void
    {
        $condition = $conditionArray["condition"];
        $callable = $conditionArray["callback"] ?? null;
        assert($callable !== null);

        if ($condition->evaluate($this->request, $this->response) === false) {
            $this->handle($this->request);

            return;
        }

        //When the Condition evaluates to true: instantiate a new/branched Harmony instance to process the conditional Middleware
        $harmony = new Harmony($this->request, $this->response);
        $callable($harmony, $this->request);

        //Add remaining Middleware of the current Harmony instance to the new/branched Harmony instance and run it
        for ($i = $this->currentMiddleware + 1; $i < count($this->middleware); $i++) {
            $harmony->middleware[] = $this->middleware[$i];
        }
        $harmony->run();

        $this->request = $harmony->request;
        $this->response = $harmony->response;
    }
}
