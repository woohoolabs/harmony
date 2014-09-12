<?php
namespace WoohooLabs\ApiFramework\Serializer;

use JMS\Serializer\SerializerBuilder;
use WoohooLabs\ApiFramework\Config;
use WoohooLabs\ApiFramework\Request\MimeTypes;

class JmsSerializer implements SerializerInterface
{
    /**
     * @var \JMS\Serializer\SerializerBuilder
     */
    private $serializer;

    /**
     * @var array
     */
    private $mediaTypes= [
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
     * @throws \WoohooLabs\ApiFramework\Serializer\SerializerException
     */
    public function serialize($data, $mediaType)
    {
        if ($this->isMediaTypeSupported($mediaType) !== true) {
            throw new MediaTypeNotSupportedException();
        }

        return $this->serializer->serialize($data, $this->getFormat($mediaType));
    }

    /**
     * @param string $data
     * @param string $mediaType
     * @param string|null $type
     * @return Object|array
     * @throws \WoohooLabs\ApiFramework\Serializer\MediaTypeNotSupportedException
     * @throws \WoohooLabs\ApiFramework\Serializer\SerializerException
     */
    public function deserialize($data, $mediaType, $type = null)
    {
        if ($this->isMediaTypeSupported($mediaType) !== true) {
            throw new MediaTypeNotSupportedException();
        }

        return $this->serializer->deserialize($data, $type, $mediaType);
    }

    /**
     * @param string $mediaType
     * @return boolean
     */
    protected function isMediaTypeSupported($mediaType)
    {
        return isset($this->mediaTypes[$mediaType]);
    }

    /**
     * @param string $mediaType
     * @return string mixed
     */
    protected function getFormat($mediaType) {
        return $this->mediaTypes[$mediaType];
    }
}
