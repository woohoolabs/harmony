<?php
namespace WoohooLabs\Harmony\Serializer\Implementations;

use WoohooLabs\Harmony\Serializer\DeserializerInterface;
use WoohooLabs\Harmony\Serializer\DeserializerTrait;
use WoohooLabs\Harmony\Serializer\FormatNotSupportedException;
use WoohooLabs\Harmony\Serializer\Formats;
use WoohooLabs\Harmony\Serializer\SerializerException;
use WoohooLabs\Harmony\Serializer\SerializerInterface;
use WoohooLabs\Harmony\Serializer\SerializerTrait;

class JsonSerializer implements SerializerInterface, DeserializerInterface
{
    use SerializerTrait;
    use DeserializerTrait;

    /**
     * @return array
     */
    public function getDeserializableFormats()
    {
        return [Formats::JSON];
    }

    /**
     * @return array
     */
    public function getSerializableFormats()
    {
        return [Formats::JSON];
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
            return json_encode($data);
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
            return json_decode($data, true);
        } catch (\Exception $e) {
            throw new SerializerException();
        }
    }
}
