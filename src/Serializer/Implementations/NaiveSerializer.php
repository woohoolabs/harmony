<?php
namespace WoohooLabs\Harmony\Serializer\Implementations;

use WoohooLabs\Harmony\Serializer\DeserializerInterface;
use WoohooLabs\Harmony\Serializer\SerializerInterface;

class NaiveSerializer implements SerializerInterface, DeserializerInterface
{
    /**
     * @param string $format
     * @return boolean
     */
    public function isFormatSerializable($format)
    {
        return true;
    }

    /**
     * @param string $format
     * @return boolean
     */
    public function isFormatDeserializable($format)
    {
        return true;
    }

    /**
     * @param string $data
     * @param string $format
     * @return string
     */
    public function serialize($data, $format)
    {
        return $data;
    }

    /**
     * @param string $data
     * @param string $format
     * @return string
     */
    public function deserialize($data, $format)
    {
        return $data;
    }
}
