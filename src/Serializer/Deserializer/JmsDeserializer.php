<?php
namespace WoohooLabs\ApiFramework\Serializer\Deserializer;

use JMS\Serializer\SerializerBuilder;
use WoohooLabs\ApiFramework\Config;
use WoohooLabs\ApiFramework\Request\MimeTypes;
use WoohooLabs\ApiFramework\Serializer\AbstractSerializer;
use WoohooLabs\ApiFramework\Serializer\MediaTypeNotSupportedException;

class JmsDeserializer extends AbstractSerializer implements DeserializerInterface
{
    /**
     * @var \JMS\Serializer\SerializerBuilder
     */
    private $deserializer;

    /**
     * @var array
     */
    protected $supportedMediaTypes= [
        MimeTypes::JSON => "json",
        MimeTypes::XML => "xml",
    ];

    public function __construct(Config $config)
    {
        $serializerBuilder= SerializerBuilder::create();
        $serializerBuilder->setDebug($config->isDevelopmentMode());
        if ($config->isCaching()) {
            $serializerBuilder->setCacheDir($config->getCacheDirectory());
        }

        $this->deserializer= $serializerBuilder->build();
    }

    /**
     * @param string $data
     * @param string $mediaType
     * @param boolean $isArray
     * @return Object|array
     * @throws \WoohooLabs\ApiFramework\Serializer\MediaTypeNotSupportedException
     */
    public function deserialize($data, $mediaType, $isArray = true)
    {
        if ($this->isMediaTypeSupported($mediaType) !== true) {
            throw new MediaTypeNotSupportedException();
        }

        return $this->deserializer->deserialize($data, $isArray == true ? null : $isArray, $this->getFormat($mediaType));
    }
}
