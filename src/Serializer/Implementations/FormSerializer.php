<?php
namespace WoohooLabs\ApiFramework\Serializer\Implementations;

use WoohooLabs\ApiFramework\Serializer\DeserializerInterface;
use WoohooLabs\ApiFramework\Serializer\DeserializerTrait;
use WoohooLabs\ApiFramework\Serializer\Formats;
use WoohooLabs\ApiFramework\Serializer\SerializerException;
use WoohooLabs\ApiFramework\Serializer\SerializerInterface;
use WoohooLabs\ApiFramework\Serializer\SerializerTrait;

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
     * @throws \WoohooLabs\ApiFramework\Serializer\FormatNotSupportedException
     * @throws \WoohooLabs\ApiFramework\Serializer\SerializerException
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
     * @throws \WoohooLabs\ApiFramework\Serializer\FormatNotSupportedException
     * @throws \WoohooLabs\ApiFramework\Serializer\SerializerException
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
