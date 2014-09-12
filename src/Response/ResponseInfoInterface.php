<?php
namespace WoohooLabs\ApiFramework\Response;

interface ResponseInfoInterface
{
    /**
     * @return string
     */
    public function getProtocolVersion();

    /**
     * @return boolean
     */
    public function isStatusCodeSuppressed();

    /**
     * @param boolean $isStatusCodeSuppressed
     */
    public function setStatusCodeSuppressed($isStatusCodeSuppressed);

    /**
     * @return string
     */
    public function getStatusCode();

    /**
     * @return string
     */
    public function getOriginalStatusCode();

    /**
     * @param string $statusCode
     */
    public function setStatusCode($statusCode);

    /**
     * @return string|null
     */
    public function getReasonPhrase();

    /**
     * @param string|null $reasonPhrase
     */
    public function setReasonPhrase($reasonPhrase);

    /**
     * @return string
     */
    public function getCharset();

    /**
     * @param string $charset
     */
    public function setCharset($charset);

    /**
     * @return string
     */
    public function getEncoding();

    /**
     * @param string $encoding
     */
    public function setEncoding($encoding);

    /**
     * @return string
     */
    public function getContentType();

    /**
     * @param string $contentType
     * @param string|null $mime
     */
    public function setContentType($contentType, $mime = null);

    /**
     * @return string
     */
    public function getMime();

    /**
     * @param string $mime
     */
    public function setMime($mime);

    /**
     * @return string
     */
    public function getLanguage();

    /**
     * @param string $language
     */
    public function setLanguage($language);

    /**
     * @return array
     */
    public function getHeaders();

    /**
     * @param string $name
     * @param string $value
     */
    public function setHeader($name, $value);

    /**
     * @return Object|array
     */
    public function getContent();
}
