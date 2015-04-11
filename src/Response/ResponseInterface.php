<?php
namespace WoohooLabs\Harmony\Response;

interface ResponseInterface
{
    /**
     * Sends itself to the ether.
     */
    public function respond();

    /**
     * @return string
     */
    public function getProtocolVersion();

    /**
     * @param string $version
     */
    public function setProtocolVersion($version);

    /**
     * @return bool
     */
    public function isStatusCodeSuppressed();

    /**
     * @param bool $isStatusCodeSuppressed
     */
    public function setStatusCodeSuppressed($isStatusCodeSuppressed);

    /**
     * @return int
     */
    public function getStatusCode();

    /**
     * @return int
     */
    public function getOriginalStatusCode();

    /**
     * @param int $statusCode
     * @param string|null $reasonPhrase
     */
    public function setStatus($statusCode, $reasonPhrase = null);

    /**
     * @return string|null
     */
    public function getContentCharset();

    /**
     * @param string $charset
     */
    public function setContentCharset($charset);

    /**
     * @return string|null
     */
    public function getContentEncoding();

    /**
     * @param string $encoding
     */
    public function setContentEncoding($encoding);

    /**
     * @return string|null
     */
    public function getContentLanguage();

    /**
     * @param string $language
     */
    public function setContentLanguage($language);

    /**
     * @return string|null
     */
    public function getContentType();

    /**
     * @return string
     */
    public function getFormat();

    /**
     * @param string $contentType
     * @param string|null $format
     */
    public function setContentType($contentType, $format = null);

    /**
     * @param array|Object $data
     * @param string $contentType
     * @param string $format
     * @return mixed
     */
    public function setSerializableContent($data, $contentType = null, $format = null);

    /**
     * @return bool
     */
    public function isRedirected();

    /**
     * @return string|null
     */
    public function getRedirectionUrl();

    /**
     * @param string $url
     * @param bool $isPermanent
     * @return mixed
     */
    public function setRedirected($url, $isPermanent = true);

    /**
     * @param string $url
     * @param bool $isPermanent
     * Redirects immediately
     */
    public function redirect($url, $isPermanent = true);

    /**
     * @return string|null
     */
    public function getFileServed();

    /**
     * @param string $fileUrl
     * @return mixed
     */
    public function setFileServed($fileUrl);

    /**
     * @return \DateTime|null
     */
    public function getExpires();
    /**
     * @param \DateTime|null $expires
     */
    public function setExpires(\DateTime $expires = null);

    /**
     * @return string|null
     */
    public function getETag();

    /**
     * @param string $eTag
     * @param bool $isWeak
     */
    public function setETag($eTag, $isWeak = false);

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
    public function setLastModified(\DateTime $lastModified = null);

    /**
     * @return array
     */
    public function getHeaders();

    /**
     * @param string $name
     * @return string
     */
    public function getHeader($name);

    /**
     * @param string $name
     * @param string $value
     */
    public function setHeader($name, $value);

    /**
     * @param string $name
     */
    public function removeHeader($name);

    /**
     * @return string
     */
    public function getBody();

    /**
     * @param string $body
     */
    public function setBody($body);
}
