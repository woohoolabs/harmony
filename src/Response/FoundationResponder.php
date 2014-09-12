<?php
namespace WoohooLabs\ApiFramework\Response;

use Symfony\Component\HttpFoundation\Response;
use WoohooLabs\ApiFramework\Config;
use WoohooLabs\ApiFramework\Serializer\SerializerInterface;

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
     * @var \WoohooLabs\ApiFramework\Serializer\SerializerInterface
     */
    private $serializer;

    public function __construct(Config $config, SerializerInterface $serializer)
    {
        $this->config= $config;
        $this->response= new Response();
        $this->serializer= $serializer;
    }

    /**
     * @param \WoohooLabs\ApiFramework\Response\ResponseInfoInterface $responseInfo
     */
    public function respond(ResponseInfoInterface $responseInfo)
    {
        $this->response->setProtocolVersion($responseInfo->getProtocolVersion());
        $this->response->setStatusCode($responseInfo->getStatusCode(), $responseInfo->getReasonPhrase());
        $this->response->headers->add($responseInfo->getHeaders());
        $this->response->setContent($this->serializer->serialize($responseInfo->getContent(), $responseInfo->getContentType()));

        $this->response->send();
    }
}
