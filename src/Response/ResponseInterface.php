<?php
namespace WoohooLabs\ApiFramework\Response;

interface ResponseInterface
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
    public function getContentCharset();

    /**
     * @param string $charset
     */
    public function setContentCharset($charset);

    /**
     * @return string
     */
    public function getContentEncoding();

    /**
     * @param string $encoding
     */
    public function setContentEncoding($encoding);

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
    public function getContentLanguage();

    /**
     * @param string $language
     */
    public function setContentLanguage($language);

    /**
     * @return \DateTime|null
     */
    public function getExpires();
    /**
     * @param \DateTime|null $expires
     */
    public function setExpires($expires);

    /**
     * @return null|string
     */
    public function getETag();

    /**
     * @param string|null $eTag
     */
    public function setETag($eTag);

    /**
     * @return int
     */
    public function getTtl();
    /**
     * @param int $ttl
     */
    public function setTtl($ttl);

    /**
     * @return int
     */
    public function getMaxAge();

    /**
     * @param int $maxAge
     */
    public function setMaxAge($maxAge);

    /**
     * @return \DateTime|null
     */
    public function getLastModified();

    /**
     * @param \DateTime|null $lastModified
     */
    public function setLastModified($lastModified);

    /**
     * @return array
     */
    public function getCustomHeaders();

    /**
     * @param string $name
     * @param string $value
     */
    public function setCustomHeader($name, $value);

    /**
     * @return Object|array
     */
    public function getContent();
}
