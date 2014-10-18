<?php
namespace WoohooLabs\ApiFramework\Response;

class Response implements ResponseInterface
{
    /**
     * @var string
     */
    private $contentType= "";

    /**
     * @var string
     */
    private $contentCharset= "UTF-8";

    /**
     * @var string
     */
    private $contentLanguage= "en";

    /**
     * @var string
     */
    private $contentEncoding= "";

    /**
     * @var string|null
     */
    private $mime= null;

    /**
     * @var string|null
     */
    private $location= null;

    /**
     * @var \DateTime|null
     */
    private $expires= null;

    /**
     * @var string|null
     */
    private $eTag= null;

    /**
     * @var int
     */
    private $ttl= 0;

    /**
     * @var int
     */
    private $maxAge= 0;

    /**
     * @var \DateTime|null
     */
    private $lastModified= null;

    /**
     * @var string
     */
    private $customHeaders= [];

    /**
     * @var string
     */
    private $protocolVersion= "1.1";

    /**
     * @var boolean
     */
    private $suppressStatusCode= false;

    /**
     * @var int
     */
    private $statusCode= 200;

    /**
     * @var string|null
     */
    private $reasonPhrase= null;

    /**
     * @var Object|array
     */
    private $content= array();

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     * @param string|null $mime
     */
    public function setContentType($contentType, $mime = null)
    {
        if ($mime === null) {
            $this->setMime($contentType);
        }
        $this->contentType = $contentType;
    }

    /**
     * @return string
     */
    public function getContentCharset()
    {
        return $this->contentCharset;
    }

    /**
     * @param string $contentCharset
     */
    public function setContentCharset($contentCharset)
    {
        $this->contentCharset = $contentCharset;
    }

    /**
     * @return string
     */
    public function getContentLanguage()
    {
        return $this->contentLanguage;
    }

    /**
     * @param string $contentLanguage
     */
    public function setContentLanguage($contentLanguage)
    {
        $this->contentLanguage = $contentLanguage;
    }

    /**
     * @return string
     */
    public function getContentEncoding()
    {
        return $this->contentEncoding;
    }

    /**
     * @param string $contentEncoding
     */
    public function setContentEncoding($contentEncoding)
    {
        $this->contentEncoding = $contentEncoding;
    }

    /**
     * @return string
     */
    public function getMime()
    {
        if ($this->mime === null) {
            return $this->getContentType();
        }

        return $this->mime;
    }

    /**
     * @param string $mime
     */
    public function setMime($mime)
    {
        $this->mime = $mime;
    }

    /**
     * @return boolean
     */
    public function isRedirected()
    {
        return ($this->getStatusCode() === 304 || $this->getStatusCode() == 307) && $this->location != null;
    }

    /**
     * @return string|null
     */
    public function getRedirectionUrl()
    {
        return $this->location;
    }

    /**
     * @param $url
     * @param boolean $isPermanent
     */
    public function setRedirection($url, $isPermanent = true)
    {
        $this->location= $url;
        $this->setStatusCode($isPermanent === true ? 301 : 307);
    }

    /**
     * @return \DateTime|null
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @param \DateTime|null $expires
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;
    }

    /**
     * @return null|string
     */
    public function getETag()
    {
        return $this->eTag;
    }

    /**
     * @param string|null $eTag
     */
    public function setETag($eTag)
    {
        $this->eTag = $eTag;
    }

    /**
     * @return int
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * @param int $ttl
     */
    public function setTtl($ttl)
    {
        $this->ttl = $ttl;
    }

    /**
     * @return int
     */
    public function getMaxAge()
    {
        return $this->maxAge;
    }

    /**
     * @param int $maxAge
     */
    public function setMaxAge($maxAge)
    {
        $this->maxAge = $maxAge;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * @param \DateTime|null $lastModified
     */
    public function setLastModified($lastModified)
    {
        $this->lastModified = $lastModified;
    }

    /**
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    /**
     * @param string $protocolVersion
     */
    public function setProtocolVersion($protocolVersion)
    {
        $this->protocolVersion = $protocolVersion;
    }

    /**
     * @return null|string
     */
    public function getReasonPhrase()
    {
        return $this->reasonPhrase;
    }

    /**
     * @param null|string $reasonPhrase
     */
    public function setReasonPhrase($reasonPhrase)
    {
        $this->reasonPhrase = $reasonPhrase;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->isStatusCodeSuppressed() ? 200 : $this->statusCode;
    }

    /**
     * @return int
     */
    public function getOriginalStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return boolean
     */
    public function isStatusCodeSuppressed()
    {
        return $this->suppressStatusCode;
    }

    /**
     * @param boolean $isStatusCodeSuppressed
     */
    public function setStatusCodeSuppressed($isStatusCodeSuppressed)
    {
        $this->suppressStatusCode= $isStatusCodeSuppressed;
    }

    /**
     * @return array
     */
    public function getCustomHeaders()
    {
        return $this->customHeaders;
    }

    /**
     * @param string $name
     * @return string
     */
    public function getCustomHeader($name)
    {
        return isset($this->customHeaders[$name]) ? $this->customHeaders[$name] : "";
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setCustomHeader($name, $value)
    {
        $this->customHeaders[$name]= $value;
    }

    /**
     * @return array|Object
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param array|Object $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}
