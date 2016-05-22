<?php
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

    /**
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param string $id
     * @return callable|null
     */
    public function getMiddleware($id)
    {
        $position = $this->findMiddleware($id);

        if ($position === null) {
            return null;
        }

        return $this->middleware[$position]["callable"];
    }

    /**
     * @param callable $middleware
     * @param string|null $id
     * @return $this
     */
    public function addMiddleware(callable $middleware, $id = null)
    {
        $this->middleware[] = [
            "id" => $id,
            "callable" => $middleware
        ];

        return $this;
    }

    /**
     * @param string $id
     * @return $this
     * @throws MiddlewareNotExists
     */
    public function removeMiddleware($id)
    {
        $position = $this->findMiddleware($id);

        if ($position === null) {
            throw new MiddlewareNotExists($id);
        }

        unset($this->middleware[$position]);

        return $this;
    }

    /**
     * @param ConditionInterface $condition
     * @param callable $callableOnSuccess
     * @return $this
     */
    public function addCondition(ConditionInterface $condition, callable $callableOnSuccess)
    {
        $this->middleware[] = [
            "condition" => $condition,
            "callable" => $callableOnSuccess
        ];

        return $this;
    }

    /**
     * @param string $id
     * @return int|null
     */
    protected function findMiddleware($id)
    {
        foreach ($this->middleware as $k => $middleware) {
            if ($middleware["id"] === $id) {
                return $k;
            }
        }

        return null;
    }

    /**
     * @param array $middlewareArray
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
