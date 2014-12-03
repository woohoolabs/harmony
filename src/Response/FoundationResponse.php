<?php
namespace WoohooLabs\ApiFramework\Response;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use WoohooLabs\ApiFramework\Serializer\SerializerInterface;

class FoundationResponse implements ResponseInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\Response
     */
    private $response;

    /**
     * @var \WoohooLabs\ApiFramework\Serializer\SerializerInterface
     */
    private $serializer;

    /**
     * @var string
     */
    private $format;

    /**
     * @var boolean
     */
    private $isStatusCodeSuppressed;

    public function __construct(SerializerInterface $serializer)
    {
        $this->response= new Response();
        $this->serializer= $serializer;
        $this->isStatusCodeSuppressed= false;
    }

    /**
     * Sends itself to the ether.
     */
    public function respond()
    {
        $this->response->send();
    }

    /**
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->response->getProtocolVersion();
    }

    /**
     * @param string $version
     */
    public function setProtocolVersion($version)
    {
        $this->response->setProtocolVersion($version);
    }

    /**
     * @return boolean
     */
    public function isStatusCodeSuppressed()
    {
        return $this->isStatusCodeSuppressed;
    }

    /**
     * @param boolean $isStatusCodeSuppressed
     */
    public function setStatusCodeSuppressed($isStatusCodeSuppressed)
    {
        $this->isStatusCodeSuppressed= $isStatusCodeSuppressed;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->isStatusCodeSuppressed === true ? 200 : $this->response->getStatusCode();
    }

    /**
     * @return int
     */
    public function getOriginalStatusCode()
    {
        return $this->response->getStatusCode();
    }

    /**
     * @param int $statusCode
     * @param string|null $reasonPhrase
     */
    public function setStatus($statusCode, $reasonPhrase = null)
    {
        $this->response->setStatusCode($statusCode, $reasonPhrase);
    }

    /**
     * @return string|null
     */
    public function getContentCharset()
    {
        return $this->response->getCharset();
    }

    /**
     * @param string $charset
     */
    public function setContentCharset($charset)
    {
        $this->response->setCharset($charset);
    }

    /**
     * @return string|null
     */
    public function getContentEncoding()
    {
        return $this->response->headers->get("Content-Encoding");
    }

    /**
     * @param string $encoding
     */
    public function setContentEncoding($encoding)
    {
        $this->response->headers->set("Content-Encoding", $encoding);
    }

    /**
     * @return string|null
     */
    public function getContentLanguage()
    {
        return $this->response->headers->get("Content-Language");
    }

    /**
     * @param string $language
     */
    public function setContentLanguage($language)
    {
        $this->response->headers->set("Content-Language", $language);
    }

    /**
     * @return string|null
     */
    public function getContentType()
    {
        return $this->response->headers->get("Content-Type");
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $contentType
     * @param string|null $format
     */
    public function setContentType($contentType, $format = null)
    {
        $this->response->headers->set("Content-Type", $contentType);
        $this->format= $format !== null ? $format : $contentType;
    }

    /**
     * @param array|Object $data
     * @param string $contentType
     * @param string $format
     * @return mixed
     */
    public function setSerializableContent($data, $contentType = null, $format = null)
    {
        $this->setContentType($contentType, $format);
        $this->response->setContent($this->serializer->serialize($data, $this->getFormat()));
    }

    /**
     * @return boolean
     */
    public function isRedirected()
    {
        return $this->response->isRedirection();
    }

    /**
     * @return string|null
     */
    public function getRedirectionUrl()
    {
        return $this->response->headers->get("Location");
    }

    /**
     * @param string $url
     * @param boolean $isPermanent
     * @return mixed
     */
    public function setRedirected($url, $isPermanent = true)
    {
        $this->response->headers->set("Location", $url);
        $this->setStatus($isPermanent === true ? 301 : 307);
    }

    /**
     * Redirects immediately.
     * @param string $url
     * @param boolean $isPermanent
     */
    public function redirect($url, $isPermanent = true)
    {
        $this->setRedirected($url, $isPermanent);
        $this->respond();
    }

    /**
     * @return string|null
     */
    public function getFileServed()
    {
        return $this->response->headers->get("Content-Disposition");
    }

    /**
     * @param string $fileUrl
     * @return mixed
     */
    public function setFileServed($fileUrl)
    {
        $disposition = $this->response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileUrl);
        $this->response->headers->set('Content-Disposition', $disposition);
    }

    /**
     * @return \DateTime|null
     */
    public function getExpires()
    {
        return $this->response->getExpires();
    }

    /**
     * @param \DateTime|null $expires
     */
    public function setExpires(\DateTime $expires = null)
    {
        $this->response->setExpires($expires);
    }

    /**
     * @return string|null
     */
    public function getETag()
    {
        return $this->response->getEtag();
    }

    /**
     * @param string|null $eTag
     * @param boolean $isWeak
     */
    public function setETag($eTag, $isWeak = false)
    {
        $this->response->setEtag($eTag, $isWeak);
    }

    /**
     * @return int
     */
    public function getTtl()
    {
        return $this->response->getTtl();
    }

    /**
     * @param int $ttl
     */
    public function setTtl($ttl)
    {
        $this->response->setTtl($ttl);
    }

    /**
     * @return int
     */
    public function getMaxAge()
    {
        return $this->response->getMaxAge();
    }

    /**
     * @param int $maxAge
     */
    public function setMaxAge($maxAge)
    {
        $this->response->setMaxAge($maxAge);
    }

    /**
     * @return \DateTime|null
     */
    public function getLastModified()
    {
        return $this->response->getLastModified();
    }

    /**
     * @param \DateTime|null $lastModified
     */
    public function setLastModified(\DateTime $lastModified = null)
    {
        $this->response->setLastModified($lastModified);
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->response->headers->all();
    }

    /**
     * @param string $name
     * @return string
     */
    public function getHeader($name)
    {
        return $this->response->headers->get($name);
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setHeader($name, $value)
    {
        $this->response->headers->set($name, $value);
    }

    /**
     * @param string $name
     */
    public function removeHeader($name)
    {
        $this->response->headers->remove($name);
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->response->getContent();
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->response->setContent($body);
    }
}
