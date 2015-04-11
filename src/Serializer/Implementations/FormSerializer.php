<?php
namespace WoohooLabs\Harmony\Serializer\Implementations;

use WoohooLabs\Harmony\Serializer\DeserializerInterface;
use WoohooLabs\Harmony\Serializer\DeserializerTrait;
use WoohooLabs\Harmony\Serializer\Formats;
use WoohooLabs\Harmony\Serializer\SerializerException;
use WoohooLabs\Harmony\Serializer\SerializerInterface;
use WoohooLabs\Harmony\Serializer\SerializerTrait;

class FormSerializer implements SerializerInterface, DeserializerInterface
{
    use SerializerTrait;
    use DeserializerTrait;

    /**
     * @return array
     */
    public function getDeserializableFormats()
    {
        return [Formats::FORM];
    }

    /**
     * @return array
     */
    public function getSerializableFormats()
    {
        return [Formats::FORM];
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
        try {
            return http_build_query($data);
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
        try {
            return $data;
        } catch (\Exception $e) {
            throw new SerializerException();
        }
    }
}
