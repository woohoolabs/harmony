<?php
namespace WoohooLabs\Harmony;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
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

    /**
     * @var int
     */
    protected $currentFinalMiddleware = -1;

    /**
     * @var bool
     */
    protected $stopped = false;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->response = $response;
        $this->request = $request;
    }

    /**
     *  Executes final middleware.
     */
    public function __destruct()
    {
        if ($this->stopped === false) {
            $this->stopped = true;
            $this->__invoke();
        }
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

        // Retrieving the key of the next normal or final middleware
        if ($this->stopped === true) {
            $nextKey = &$this->currentFinalMiddleware;
        } else {
            $nextKey = &$this->currentMiddleware;
        }
        $nextKey = $this->getNextMiddlewareKey($nextKey + 1, $this->stopped);

        // Stopping if there aren't any executable middleware remaining
        if ($nextKey === null) {
            return $this->response;
        }

        // Executing the next middleware or condition
        if (empty($this->middleware[$nextKey]["condition"])) {
            $this->executeMiddleware($this->middleware[$nextKey]);
        } else {
            $this->executeCondition($this->middleware[$nextKey]);
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
        $this->middleware[] = $this->createMiddleware($id, $middleware, false);

        return $this;
    }

    /**
     * @param callable $middleware
     * @param string|null $id
     * @return $this
     */
    public function addFinalMiddleware(callable $middleware, $id = null)
    {
        $this->middleware[] = $this->createMiddleware($id, $middleware, true);

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
        $this->middleware[] = $this->createConditionalMiddleware($condition, $callableOnSuccess);

        return $this;
    }

    /**
     * @param int $fromKey
     * @param bool $isFinal
     * @return int|null
     */
    protected function getNextMiddlewareKey($fromKey, $isFinal)
    {
        for (; isset($this->middleware[$fromKey]); $fromKey++) {
            if ($this->middleware[$fromKey]["final"] === $isFinal) {
                return $fromKey;
            }
        }

        return null;
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
     * @param string|null $id
     * @param callable $callable
     * @param bool $isFinal
     * @return array
     */
    protected function createMiddleware($id, callable $callable, $isFinal)
    {
        return [
            "id" => $id,
            "callable" => $callable,
            "final" => $isFinal
        ];
    }

    /**
     * @param ConditionInterface $condition
     * @param callable $callableOnSuccess
     * @return array
     */
    protected function createConditionalMiddleware(ConditionInterface $condition, callable $callableOnSuccess)
    {
        return [
            "condition" => $condition,
            "callable" => $callableOnSuccess
        ];
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

    /**
     * @param array $conditionArray
     */
    protected function executeCondition(array $conditionArray)
    {
        /** @var ConditionInterface $condition */
        $condition = $conditionArray["condition"];
        $callable = $conditionArray["callable"];

        if ($condition->evaluate($this->request, $this->response) === false) {
            return;
        }

        $harmony = new Harmony($this->request, $this->response);
        call_user_func($callable, $harmony);
        $harmony();
        $harmony->__destruct();
        $this->request = $harmony->getResponse();
        $this->response = $harmony->getResponse();
    }
}
