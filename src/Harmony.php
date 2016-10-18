<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Harmony\Condition\ConditionInterface;
use WoohooLabs\Harmony\Exception\MiddlewareNotExists;
use WoohooLabs\Harmony\Exception\MiddlewareWrongReturnType;

class Harmony
{
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @var array
     */
    protected $middleware = [];

    /**
     * @var int
     */
    protected $currentMiddleware = -1;

    public function __construct(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->response = $response;
        $this->request = $request;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request = null, ResponseInterface $response = null)
    {
        if ($request !== null) {
            $this->request = $request;
        }

        if ($response !== null) {
            $this->response = $response;
        }

        $this->currentMiddleware++;

        // Stop if there aren't any executable middleware remaining
        if (isset($this->middleware[$this->currentMiddleware]) === false) {
            return $this->response;
        }

        // Execute the next middleware or condition
        if (empty($this->middleware[$this->currentMiddleware]["condition"])) {
            $this->executeMiddleware($this->middleware[$this->currentMiddleware]);
        } else {
            $this->executeCondition($this->middleware[$this->currentMiddleware]);
        }

        return $this->response;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @return callable|null
     */
    public function getMiddleware(string $id)
    {
        $position = $this->findMiddleware($id);

        if ($position === null) {
            return null;
        }

        return $this->middleware[$position]["callable"];
    }

    public function addMiddleware(callable $middleware, string $id = null): Harmony
    {
        $this->middleware[] = [
            "id" => $id,
            "callable" => $middleware
        ];

        return $this;
    }

    /**
     * @throws MiddlewareNotExists
     */
    public function removeMiddleware(string $id): Harmony
    {
        $position = $this->findMiddleware($id);

        if ($position === null) {
            throw new MiddlewareNotExists($id);
        }

        unset($this->middleware[$position]);

        return $this;
    }

    public function addCondition(ConditionInterface $condition, callable $callableOnSuccess): Harmony
    {
        $this->middleware[] = [
            "condition" => $condition,
            "callable" => $callableOnSuccess
        ];

        return $this;
    }

    /**
     * @return int|null
     */
    protected function findMiddleware(string $id)
    {
        foreach ($this->middleware as $k => $middleware) {
            if ($middleware["id"] === $id) {
                return $k;
            }
        }

        return null;
    }

    /**
     * @throws \WoohooLabs\Harmony\Exception\MiddlewareWrongReturnType
     */
    protected function executeMiddleware(array $middlewareArray)
    {
        $middleware = $middlewareArray["callable"];

        $response = $middleware($this->getRequest(), $this->getResponse(), $this);
        if ($response instanceof ResponseInterface) {
            $this->response = $response;
        } else {
            throw new MiddlewareWrongReturnType();
        }
    }

    protected function executeCondition(array $conditionArray)
    {
        /** @var ConditionInterface $condition */
        $condition = $conditionArray["condition"];
        $callable = $conditionArray["callable"];

        if ($condition->evaluate($this->request, $this->response) === false) {
            return;
        }

        $harmony = new Harmony($this->request, $this->response);
        $callable($harmony);
        $harmony();
        $this->request = $harmony->request;
        $this->response = $harmony->response;
    }
}
