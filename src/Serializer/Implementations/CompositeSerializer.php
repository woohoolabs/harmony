<?php
namespace WoohooLabs\Harmony\Serializer\Implementations;

use WoohooLabs\Harmony\Config;
use WoohooLabs\Harmony\Serializer\DeserializerInterface;
use WoohooLabs\Harmony\Serializer\FormatNotSupportedException;
use WoohooLabs\Harmony\Serializer\SerializerException;
use WoohooLabs\Harmony\Serializer\SerializerInterface;
use WoohooLabs\Harmony\Serializer\TwoWaySerializerInterface;

class CompositeSerializer implements TwoWaySerializerInterface
{
    /**
     * @var array
     */
    protected $serializers= [];

    /**
     * @var array
     */
    protected $deserializers= [];

    /**
     * @var \WoohooLabs\Harmony\Config
     */
    protected $config;

    /**
     * @param \WoohooLabs\Harmony\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config= $config;
    }

    /**
     * @param string $format
     * @return boolean
     */
    public function isFormatSerializable($format)
    {
        return isset($this->serializers[$format]);
    }

    /**
     * @param string $format
     * @return boolean
     */
    public function isFormatDeserializable($format)
    {
        return isset($this->deserializers[$format]);
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
        if ($this->isFormatSerializable($format) !== true) {
            throw new FormatNotSupportedException();
        }

        $serializer= call_user_func($this->serializers[$format]);

        if ($serializer instanceof SerializerInterface) {
            return $serializer->serialize($data, $format);
        }

        throw new SerializerException();
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
            throw new FormatNotSupportedException();
        }

        $deserializer= call_user_func($this->deserializers[$format]);

        if ($deserializer instanceof DeserializerInterface) {
            return $deserializer->deserialize($data, $format);
        }

        throw new SerializerException();
    }

    /**
     * @param string $format
     * @param \Closure $serializerFactory
     */
    public function addSerializer($format, \Closure $serializerFactory)
    {
        $this->serializers[$format]= $serializerFactory;
    }

    /**
     * @param array $formats
     * @param \Closure $serializerFactory
     */
    public function addMultiSerializer(array $formats, \Closure $serializerFactory)
    {
        foreach ($formats as $format) {
            $this->serializers[$format]= $serializerFactory;
        }
    }

    /**
     * @param string $format
     * @param \Closure $deserializerFactory
     */
    public function addDeserializer($format, \Closure $deserializerFactory)
    {
        $this->deserializers[$format]= $deserializerFactory;
    }

    /**
     * @param array $formats
     * @param \Closure $deserializerFactory
     */
    public function addMultiDeserializer($formats, \Closure $deserializerFactory)
    {
        foreach ($formats as $format) {
            $this->deserializers[$format] = $deserializerFactory;
        }
    }
}
