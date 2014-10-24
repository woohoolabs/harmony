<?php
namespace WoohooLabs\ApiFramework\Serializer;

interface DeserializerInterface
{
    /**
     * @param string $data
     * @param string $format
     * @return array
     * @throws \WoohooLabs\ApiFramework\Serializer\FormatNotSupportedException
     * @throws \WoohooLabs\ApiFramework\Serializer\SerializerException
     */
    public function deserialize($data, $format);

    /**
     * @param string $format
     * @return boolean
     */
    public function isFormatDeserializable($format);
}
