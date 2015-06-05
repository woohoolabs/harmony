<?php
namespace WoohooLabs\Harmony\Request;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use WoohooLabs\Harmony\Serializer\DeserializerInterface;
use WoohooLabs\Harmony\Serializer\Formats;
use WoohooLabs\Harmony\Serializer\Implementations\JmsSerializer;

class FoundationRequest implements RequestInterface
{
    /**
     * @var \WoohooLabs\Harmony\Serializer\DeserializerInterface
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

    /**
     * @var string
     */
    private $route;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \WoohooLabs\Harmony\Serializer\DeserializerInterface $deserializer
     */
    public function __construct(Request $request, DeserializerInterface $deserializer = null)
    {
        $this->request= $request;
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
     * @param string $key
     * @param mixed $default
     * @return string|null
     */
    public function getQueryStringProperty($key, $default = null)
    {
        return $this->request->query->get($key, $default);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->request->getRequestUri();
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->request->getPathInfo();
    }

    /**
     * @return string|null
     */
    public function getFormat()
    {
        return $this->request->getContentType();
    }

    /**
     * @return string|null
     */
    public function getContentType()
    {
        return $this->request->getContentType();
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
     * @return bool
     */
    public function isXmlHttpRequest()
    {
        return $this->request->isXmlHttpRequest();
    }

    /**
     * @return string|null
     */
    public function getAuthorizationHeader()
    {
        return $this->request->headers->get("Authorization", null);
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return string|null
     */
    public function getHeader($name, $default = null)
    {
        return $this->request->headers->get($name, $default);
    }

    /**
     * @return array
     */
    public function getAllHeaders()
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

    public function startSession()
    {
        $this->request->setSession(new Session());
        $this->request->getSession()->start();
    }

    public function closeSession()
    {
        session_destroy();
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getSession($key, $default = null)
    {
        return $this->request->getSession()->get($key, $default);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function hasSession($key)
    {
        return $this->request->getSession()->has($key);
    }

    /**
     * @return array
     */
    public function getSessions()
    {
        return $this->request->getSession()->all();
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function setSession($key, $value)
    {
        $this->request->getSession()->set($key, $value);
    }

    /**
     * @param array $keys
     * @return mixed
     */
    public function setSessions(array $keys)
    {
        $this->request->getSession()->replace($keys);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function removeSession($key)
    {
        $this->request->getSession()->remove($key);
    }

    public function clearSession()
    {
        $this->request->getSession()->clear();
    }

    public function getCookie($name)
    {
        return $this->request->cookies->get($name);
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
    public function getBodyDeserialized()
    {
        return $this->getDeserializer()->deserialize($this->getBody(), $this->getFormat());
    }

    /**
     * @return array
     */
    public function getDataDeserialized()
    {
        if ($this->requestParameters === null) {
            if ($this->getMethod() == HttpMethods::GET || HttpMethods::HEAD || HttpMethods::DELETE) {
                $this->requestParameters = $this->getQueryStringAsArray();
            } else {
                $this->requestParameters = $this->getBodyDeserialized();
            }
        }

        return $this->requestParameters;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasFormData($key)
    {
        return $this->request->request->has($key);
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getFormData($key, $default = null)
    {
        return $this->request->request->get($key, $default);
    }

    /**
     * @return \WoohooLabs\Harmony\Serializer\DeserializerInterface
     */
    public function getDeserializer()
    {
        if ($this->deserializer === null) {
            $this->deserializer = new JmsSerializer();
        }

        return $this->deserializer;
    }
}
