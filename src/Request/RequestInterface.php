<?php
namespace WoohooLabs\ApiFramework\Request;

interface RequestInterface
{
    /**
     * @return string
     * @example GET
     */
    public function getMethod();

    /**
     * @return string
     * @example http
     */
    public function getScheme();

    /**
     * @return string
     * @example example.com
     */
    public function getHost();

    /**
     * @return string
     * @example http://example.com
     */
    public function getSchemeAndHost();

    /**
     * @return string
     * @example 8080
     */
    public function getPort();

    /**
     * @return string
     * @example /users/1
     */
    public function getUri();

    /**
     * @return array
     * @example ['user' => 1]
     */
    public function getUriParameters();

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getUriParameter($name);

    /**
     * @param array $parameters
     */
    public function setUriParameters(array $parameters);

    /**
     * @return string
     * @example page=1&per_page=10
     */
    public function getQueryString();

    /**
     * @return array
     * @example ['page' => 1, 'per_page' => 10]
     */
    public function getQueryStringAsArray();

    /**
     * @param string $key
     * @return string|null
     */
    public function getQueryStringProperty($key);

    /**
     * @return string
     * @example /users/1?page=1&per_page=10
     */
    public function getPath();

    /**
     * @return string|null
     * @example /users/{userId}
     */
    public function getRoute();

    /**
     * @param string $route
     */
    public function setRoute($route);

    /**
     * @return string
     * @example http://example.com:8080/users/1
     */
    public function getUrl();

    /**
     * @return string|null
     * @example json
     */
    public function getFormat();

    /**
     * @return string|null
     * @example application/json
     */
    public function getContentType();

    /**
     * @return array
     */
    public function getAcceptableLanguages();

    /**
     * @return array
     */
    public function getAcceptableCharsets();

    /**
     * @return array
     */
    public function getAcceptableEncodings();

    /**
     * @return array
     */
    public function getAcceptableMediaTypes();

    /**
     * @return boolean
     */
    public function isXmlHttpRequest();

    /**
     * @return array
     */
    public function getETags();

    /**
     * @return boolean
     */
    public function isNoCache();

    /**
     * @return string|null
     */
    public function getAuthorizationHeader();

    /**
     * @return array
     */
    public function getAllHeaders();

    /**
     * @param string $name
     * @return string|null
     */
    public function getHeader($name);

    public function startSession();

    public function closeSession();

    /**
     * @param string $key
     * @return mixed
     */
    public function getSession($key);

    /**
     * @return array
     */
    public function getSessions();

    /**
     * @param string $key
     * @return mixed
     */
    public function hasSession($key);

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function setSession($key, $value);

    /**
     * @param array $keys
     * @return mixed
     */
    public function setSessions(array $keys);

    /**
     * @param string $key
     * @return mixed
     */
    public function removeSession($key);

    public function clearSession();

    /**
     * @return string
     */
    public function getBody();

    /**
     * @return array
     */
    public function getBodyDeserialized();

    /**
     * @return array
     */
    public function getDataDeserialized();

    /**
     * @param string $key
     * @return boolean
     */
    public function hasFormData($key);

    /**
     * @param string $key
     * @return mixed
     */
    public function getFormData($key);
}
