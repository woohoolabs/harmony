<?php
namespace WoohooLabs\ApiFramework\Serializer\Implementations;

use WoohooLabs\ApiFramework\Serializer\DeserializerInterface;
use WoohooLabs\ApiFramework\Serializer\DeserializerTrait;
use WoohooLabs\ApiFramework\Serializer\FormatNotSupportedException;
use WoohooLabs\ApiFramework\Serializer\Formats;
use WoohooLabs\ApiFramework\Serializer\SerializerException;
use WoohooLabs\ApiFramework\Serializer\SerializerInterface;
use WoohooLabs\ApiFramework\Serializer\SerializerTrait;

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
     * @throws \WoohooLabs\ApiFramework\Serializer\FormatNotSupportedException
     * @throws \WoohooLabs\ApiFramework\Serializer\SerializerException
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
     * @throws \WoohooLabs\ApiFramework\Serializer\FormatNotSupportedException
     * @throws \WoohooLabs\ApiFramework\Serializer\SerializerException
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
