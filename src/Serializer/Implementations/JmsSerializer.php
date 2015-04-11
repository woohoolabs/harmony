<?php
namespace WoohooLabs\Harmony\Serializer\Implementations;

use JMS\Serializer\Exception\UnsupportedFormatException;
use JMS\Serializer\SerializerBuilder;
use WoohooLabs\Harmony\Serializer\DeserializerTrait;
use WoohooLabs\Harmony\Serializer\FormatNotSupportedException;
use WoohooLabs\Harmony\Serializer\Formats;
use WoohooLabs\Harmony\Serializer\SerializerException;
use WoohooLabs\Harmony\Serializer\SerializerTrait;
use WoohooLabs\Harmony\Serializer\TwoWaySerializerInterface;

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
     * @param bool $isDebug
     */
    public function __construct($isDebug = false)
    {
        $serializerBuilder= SerializerBuilder::create();
        $serializerBuilder->setDebug($isDebug);
        $this->serializer= $serializerBuilder->build();
    }

    /**
     * @param array|Object $data
     * @param string $format
     * @return string
     * @throws \WoohooLabs\Harmony\Serializer\FormatNotSupportedException
     * @throws \WoohooLabs\Harmony\Serializer\SerializerException
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
     * @throws \WoohooLabs\Harmony\Serializer\FormatNotSupportedException
     * @throws \WoohooLabs\Harmony\Serializer\SerializerException
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
