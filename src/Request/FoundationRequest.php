<?php
namespace WoohooLabs\ApiFramework\Request;

use WoohooLabs\ApiFramework\Serializer\Deserializer\DeserializerInterface;
use Symfony\Component\HttpFoundation\Request;
use WoohooLabs\ApiFramework\Config;

class FoundationRequest implements RequestInterface
{
    /**
     * @var \WoohooLabs\ApiFramework\Serializer\Deserializer\DeserializerInterface
     */
    private $deserializer;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var array
     */
    private $pathParameters;

    /**
     * @var array
     */
    private $requestParameters;

    public function __construct(Config $config, DeserializerInterface $deserializer)
    {
        $this->request= Request::createFromGlobals();
        if ($config->isHttpMethodParameterOverrideSupported()) {
            $this->request->enableHttpMethodParameterOverride();
        }
        $this->deserializer= $deserializer;
    }

    /**
     * @return string
     * @example http
     */
    public function getScheme()
    {
        return $this->request->getScheme();
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->request->getHost();
    }

    /**
     * @return string
     * @example http://example.com
     */
    public function getSchemeAndHost()
    {
        return $this->request->getSchemeAndHttpHost();
    }

    /**
     * @return string
     */
    public function getPort()
    {
        return $this->request->getPort();
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->request->getUri();
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->request->getMethod();
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->request->getPathInfo();
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->request->getRequestUri();
    }

    /**
     * @return array
     */
    public function getUriParameters()
    {
        return $this->pathParameters;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getUriParameter($name)
    {
        return isset($this->pathParameters[$name]) ? $this->pathParameters[$name] : null;
    }

    /**
     * @param array $pathParameters
     */
    public function setUriParameters(array $pathParameters)
    {
        $this->pathParameters = $pathParameters;
    }

    /**
     * @return string
     */
    public function getQueryString()
    {
        return $this->request->getQueryString();
    }

    /**
     * @return array
     */
    public function getQueryStringAsArray()
    {
        $result= [];
        parse_str($this->request->getQueryString(), $result);

        return $result;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->request->getContent();
    }

    /**
     * @return array
     */
    public function getBodyAsArray()
    {
        return $this->deserializer->deserialize($this->getBody(), $this->getContentType());
    }

    /**
     * @param string $type
     * @return array
     */
    public function getBodyAsObject($type)
    {
        return $this->deserializer->deserialize($this->getBody(), $this->getContentType(), $type);
    }

    /**
     * @return string|null
     */
    public function getContentType()
    {
        return $this->request->headers->get("Content-Type");
    }

    /**
     * @return array
     */
    public function getAcceptableLanguages()
    {
        return $this->request->getLanguages();
    }

    /**
     * @return array
     */
    public function getAcceptableCharsets()
    {
        return $this->request->getCharsets();
    }

    /**
     * @return array
     */
    public function getAcceptableEncodings()
    {
        return $this->request->getEncodings();
    }

    /**
     * @return array
     */
    public function getAcceptableMediaTypes()
    {
        return $this->request->getAcceptableContentTypes();
    }

    /**
     * @return boolean
     */
    public function isXmlHttpRequest()
    {
        return $this->request->isXmlHttpRequest();
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getCustomHeader($name)
    {
        return $this->request->headers->get($name, null);
    }

    /**
     * @return array
     */
    public function getCustomHeaders()
    {
        return $this->request->headers->all();
    }

    /**
     * @return array
     */
    public function getETags()
    {
        return $this->request->getETags();
    }

    public function isNoCache()
    {
        return $this->request->isNoCache();
    }

    /**
     * @return array
     */
    public function getDataAsArray()
    {
        if ($this->requestParameters == null) {
            if ($this->getMethod() == HttpMethods::GET || HttpMethods::HEAD || HttpMethods::DELETE) {
                $this->requestParameters = $this->getQueryStringAsArray();
            } else {
                $this->requestParameters = $this->getBodyAsArray();
            }
        }

        return $this->requestParameters;
    }
}
