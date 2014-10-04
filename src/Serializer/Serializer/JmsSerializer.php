<?php
namespace WoohooLabs\ApiFramework\Serializer\Serializer;

use JMS\Serializer\SerializerBuilder;
use WoohooLabs\ApiFramework\Config;
use WoohooLabs\ApiFramework\Request\MimeTypes;
use WoohooLabs\ApiFramework\Serializer\AbstractSerializer;
use WoohooLabs\ApiFramework\Serializer\MediaTypeNotSupportedException;

class JmsSerializer extends AbstractSerializer implements SerializerInterface
{
    /**
     * @var \JMS\Serializer\SerializerBuilder
     */
    private $serializer;

    /**
     * @var array
     */
    protected $supportedMediaTypes= [
        MimeTypes::JSON => "json",
        MimeTypes::YML => "yml",
        MimeTypes::XML => "xml",
    ];

    public function __construct(Config $config)
    {
        $serializerBuilder= SerializerBuilder::create();
        $serializerBuilder->setDebug($config->isDevelopmentMode());
        if ($config->isCaching()) {
            $serializerBuilder->setCacheDir($config->getCacheDirectory());
        }

        $this->serializer= $serializerBuilder->build();
    }

    /**
     * @param array|Object $data
     * @param string $mediaType
     * @return string
     * @throws \WoohooLabs\ApiFramework\Serializer\MediaTypeNotSupportedException
     */
    public function serialize($data, $mediaType)
    {
        if ($this->isMediaTypeSupported($mediaType) !== true) {
            throw new MediaTypeNotSupportedException();
        }

        return $this->serializer->serialize($data, $this->getFormat($mediaType));
    }
}
