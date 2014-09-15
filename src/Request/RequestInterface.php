<?php
namespace WoohooLabs\ApiFramework\Request;

interface RequestInterface
{
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
     */
    public function getPort();

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return string
     */
    public function getUri();

    /**
     * @return string
     */
    public function getPath();

    /**
     * @return array
     */
    public function getPathParameters();

    /**
     * @param array $parameters
     */
    public function setPathParameters(array $parameters);

    /**
     * @return string
     */
    public function getQueryString();

    /**
     * @return array
     */
    public function getQueryStringAsArray();

    /**
     * @return string
     */
    public function getBody();

    /**
     * @return array
     */
    public function getBodyAsArray();

    /**
     * @param string $type
     * @return array
     */
    public function getBodyAsObject($type);

    /**
     * @return string|null
     */
    public function getMediaType();

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
     * @param string $name
     * @return string|null
     */
    public function getHeader($name);

    /**
     * @return array
     */
    public function getDataAsArray();
}
