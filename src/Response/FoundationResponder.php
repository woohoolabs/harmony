<?php
namespace WoohooLabs\ApiFramework\Response;

use Symfony\Component\HttpFoundation\Response;
use WoohooLabs\ApiFramework\Config;
use WoohooLabs\ApiFramework\Serializer\Serializer\SerializerInterface;

class FoundationResponder implements ResponderInterface
{
    /**
     * @var \WoohooLabs\ApiFramework\Config
     */
    private $config;

    /**
     * @var \Symfony\Component\HttpFoundation\Response
     */
    private $response;

    /**
     * @var \WoohooLabs\ApiFramework\Serializer\Serializer\SerializerInterface
     */
    private $serializer;

    public function __construct(Config $config, SerializerInterface $serializer)
    {
        $this->config= $config;
        $this->response= new Response();
        $this->serializer= $serializer;
    }

    /**
     * @param \WoohooLabs\ApiFramework\Response\ResponseInterface $response
     */
    public function respond(ResponseInterface $response)
    {
        $this->response->setProtocolVersion($response->getProtocolVersion());
        $this->response->setStatusCode($response->getStatusCode(), $response->getReasonPhrase());

        $this->response->setCharset($response->getContentCharset());
        $this->response->setEtag($response->getETag());
        $this->response->setExpires($response->getExpires());
        $this->response->setMaxAge($response->getMaxAge());
        $this->response->setTtl($response->getTtl());

        $this->response->setContent($this->serializer->serialize($response->getContent(), $response->getContentType()));

        $this->response->send();
    }
}
