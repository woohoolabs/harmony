<?php
namespace WoohooLabs\ApiFramework\Response;

use WoohooLabs\ApiFramework\Request\HttpHeaders;

class ResponseInfo implements ResponseInfoInterface
{
    /**
     * @var string
     */
    private $protocolVersion= "1.1";

    /**
     * @var boolean
     */
    private $suppressStatusCode= false;

    /**
     * @var string
     */
    private $statusCode= 200;

    /**
     * @var string|null
     */
    private $reasonPhrase= null;

    /**
     * @var string
     */
    private $headers= [];

    /**
     * @var string|null
     */
    private $mime= null;

    /**
     * @var Object|array
     */
    private $content= array();

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->getHeader(HttpHeaders::CHARSET);
    }

    /**
     * @param string $charset
     */
    public function setCharset($charset)
    {
        $this->headers[HttpHeaders::CHARSET] = $charset;
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

    /**
     * @return string
     */
    public function getMime()
    {
        if ($this->mime == null) {
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
     * @return string
     */
    public function getEncoding()
    {
        return $this->getHeader(HttpHeaders::ENCODING);
    }

    /**
     * @param string $encoding
     */
    public function setEncoding($encoding)
    {
        $this->headers[HttpHeaders::ENCODING] = $encoding;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->getHeader(HttpHeaders::LANGUAGE);
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->headers[HttpHeaders::LANGUAGE] = $language;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->getHeader(HttpHeaders::CONTENT_TYPE);
    }

    /**
     * @param string $contentType
     * @param string|null $mime
     */
    public function setContentType($contentType, $mime = null)
    {
        if ($mime == null) {
            $this->setMime($contentType);
        }
        $this->headers[HttpHeaders::CONTENT_TYPE] = $contentType;
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
     * @return string
     */
    public function getStatusCode()
    {
        return $this->isStatusCodeSuppressed() ? 200 : $this->statusCode;
    }

    /**
     * @return string
     */
    public function getOriginalStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param string $statusCode
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
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $name
     * @return string
     */
    public function getHeader($name)
    {
        return isset($this->headers[$name]) ? $this->headers[$name] : "";
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setHeader($name, $value)
    {
        $this->headers[$name]= $value;
    }
}
