<?php
namespace WoohooLabs\Harmony;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Harmony\Exception\MiddlewareNotFound;

class Harmony
{
    /**
     * @var array
     */
    protected $middlewares = [];

    /**
     * @var int
     */
    protected $currentMiddleware = -1;

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

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
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __invoke(ServerRequestInterface $request = null, ResponseInterface $response = null)
    {
        if ($request !== null) {
            $this->request = $request;
        }

        if ($response !== null) {
            $this->response = $response;
        }

        if (isset($this->middlewares[$this->currentMiddleware+1])) {
            $this->executeMiddleware(++$this->currentMiddleware);
        }
    }

    /**
     * @param string $id
     * @throws \WoohooLabs\Harmony\Exception\MiddlewareNotFound
     */
    public function skipTo($id)
    {
        $position = $this->findMiddleware($id);

        if ($position === null) {
            throw new MiddlewareNotFound();
        }

        $this->currentMiddleware = $position;
        $this->executeMiddleware($this->currentMiddleware);
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

        return $this->middlewares[$position]["callable"];
    }

    /**
     * @param string $id
     * @param callable $middleware
     * @return $this
     */
    public function addMiddleware($id, callable $middleware)
    {
        $this->middlewares[] = ["id" => $id, "callable" => $middleware];

        return $this;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function removeMiddleware($id)
    {
        $position = $this->findMiddleware($id);

        if ($position !== null) {
            unset($this->middlewares[$position]);
        }

        return $this;
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
     * @param int $position
     */
    protected function executeMiddleware($position)
    {
        $this->middlewares[$position]["callable"]($this->getRequest(), $this->getResponse(), $this);
    }

    /**
     * @param string $id
     * @return int|null
     */
    protected function findMiddleware($id)
    {
        foreach ($this->middlewares as $k => $middleware) {
            if ($middleware["id"] === $id) {
                return $k;
            }
        }

        return null;
    }
}
