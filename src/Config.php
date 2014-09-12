<?php
namespace WoohooLabs\ApiFramework;

use WoohooLabs\ApiFramework\Request\MimeTypes;

class Config
{
    /**
     * @var boolean
     */
    private $developmentMode= false;

    /**
     * @var boolean
     */
    private $caching = false;

    /**
     * @var string
     */
    private $cacheDirectory= "";

    /**
     * @var array
     */
    private $supportedMediaTypes= [MimeTypes::JSON, MimeTypes::YML];

    /**
     * @var boolean
     */
    private $httpMethodParameterOverrideSupported= true;

    /**
     * @var string|null
     */
    private $preHandlerHookName= "preHook";

    /**
     * @var string|null
     */
    private $postHandlerHookName= "postHook";

    /**
     * @return string
     */
    public function getCacheDirectory()
    {
        return $this->cacheDirectory;
    }

    /**
     * @param string $cacheDirectory
     */
    public function setCacheDirectory($cacheDirectory)
    {
        $this->cacheDirectory = $cacheDirectory;
    }

    /**
     * @return boolean
     */
    public function isCaching()
    {
        return $this->caching;
    }

    /**
     * @param boolean $isCaching
     */
    public function setCaching($isCaching)
    {
        $this->caching = $isCaching;
    }

    /**
     * @return boolean
     */
    public function isDevelopmentMode()
    {
        return $this->developmentMode;
    }

    /**
     * @param boolean $isDevelopmentMode
     */
    public function setDevelopmentMode($isDevelopmentMode)
    {
        $this->developmentMode = $isDevelopmentMode;
    }

    /**
     * @return array
     */
    public function getSupportedMediaTypes()
    {
        return $this->supportedMediaTypes;
    }

    /**
     * @param array $supportedMediaTypes
     */
    public function setSupportedMediaTypes($supportedMediaTypes)
    {
        $this->supportedMediaTypes = $supportedMediaTypes;
    }

    /**
     * @return boolean
     */
    public function isHttpMethodParameterOverrideSupported()
    {
        return $this->httpMethodParameterOverrideSupported;
    }

    /**
     * @param boolean $isHttpMethodParameterOverrideSupported
     */
    public function setHttpMethodParameterOverrideSupported($isHttpMethodParameterOverrideSupported)
    {
        $this->httpMethodParameterOverrideSupported = $isHttpMethodParameterOverrideSupported;
    }

    /**
     * @return null|string
     */
    public function getPostHandlerHookName()
    {
        return $this->postHandlerHookName;
    }

    /**
     * @param null|string $postHandlerHookName
     */
    public function setPostHandlerHookName($postHandlerHookName)
    {
        $this->postHandlerHookName = $postHandlerHookName;
    }

    /**
     * @return null|string
     */
    public function getPreHandlerHookName()
    {
        return $this->preHandlerHookName;
    }

    /**
     * @param null|string $preHandlerHookName
     */
    public function setPreHandlerHookName($preHandlerHookName)
    {
        $this->preHandlerHookName = $preHandlerHookName;
    }
}
