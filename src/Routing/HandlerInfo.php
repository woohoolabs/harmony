<?php
namespace WoohooLabs\ApiFramework\Routing;

class HandlerInfo
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $methodName;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @param string $className
     * @param string $methodName
     * @param array $parameters
     */
    public function __construct($className, $methodName, array $parameters)
    {
        $this->className = $className;
        $this->methodName = $methodName;
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
