<?php
namespace WoohooLabs\Harmony\Serializer;

interface DeserializerInterface
{
    /**
     * @param string $data
     * @param string $format
     * @return array
     * @throws \WoohooLabs\Harmony\Serializer\FormatNotSupportedException
     * @throws \WoohooLabs\Harmony\Serializer\SerializerException
     */
    public function deserialize($data, $format);

    /**
     * @param string $format
     * @return bool
     */
    public function isFormatDeserializable($format);
}
