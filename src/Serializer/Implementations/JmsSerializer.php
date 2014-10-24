<?php
namespace WoohooLabs\ApiFramework\Serializer\Implementations;

use JMS\Serializer\Exception\UnsupportedFormatException;
use JMS\Serializer\SerializerBuilder;
use WoohooLabs\ApiFramework\Config;
use WoohooLabs\ApiFramework\Serializer\DeserializerTrait;
use WoohooLabs\ApiFramework\Serializer\FormatNotSupportedException;
use WoohooLabs\ApiFramework\Serializer\Formats;
use WoohooLabs\ApiFramework\Serializer\SerializerException;
use WoohooLabs\ApiFramework\Serializer\SerializerTrait;
use WoohooLabs\ApiFramework\Serializer\TwoWaySerializerInterface;

class JmsSerializer implements TwoWaySerializerInterface
{
    use SerializerTrait;
    use DeserializerTrait;

    /**
     * @var \JMS\Serializer\Serializer
     */
    private $serializer;

    /**
     * @return array
     */
    public function getDeserializableFormats()
    {
        return [Formats::JSON, Formats::XML];
    }

    /**
     * @return array
     */
    public function getSerializableFormats()
    {
        return [Formats::JSON, Formats::XML, Formats::YML];
    }

    /**
     * @param \WoohooLabs\ApiFramework\Config $config
     */
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
     * @param string $format
     * @return string
     * @throws \WoohooLabs\ApiFramework\Serializer\FormatNotSupportedException
     * @throws \WoohooLabs\ApiFramework\Serializer\SerializerException
     */
    public function serialize($data, $format)
    {
        if ($this->isFormatDeserializable($format) !== true) {
            throw new FormatNotSupportedException;
        }

        try {
            return $this->serializer->serialize($data, $format);
        } catch (UnsupportedFormatException $e) {
            throw new FormatNotSupportedException();
        } catch (\Exception $e) {
            throw new SerializerException();
        }
    }

    /**
     * @param string $data
     * @param string $format
     * @return array
     * @throws \WoohooLabs\ApiFramework\Serializer\FormatNotSupportedException
     * @throws \WoohooLabs\ApiFramework\Serializer\SerializerException
     */
    public function deserialize($data, $format)
    {
        if ($this->isFormatDeserializable($format) !== true) {
            throw new FormatNotSupportedException;
        }

        try {
            return $this->serializer->deserialize($data, null, $format);
        } catch (UnsupportedFormatException $e) {
            throw new FormatNotSupportedException();
        } catch (\Exception $e) {
            throw new SerializerException();
        }
    }
}
