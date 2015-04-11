<?php
namespace WoohooLabs\Harmony\Serializer;

interface SerializerInterface
{
    /**
     * @param mixed $data
     * @param string $format
     * @return string
     * @throws \WoohooLabs\Harmony\Serializer\FormatNotSupportedException
     * @throws \WoohooLabs\Harmony\Serializer\SerializerException
     */
    public function serialize($data, $format);

    /**
     * @param string $format
     * @return boolean
     */
    public function isFormatSerializable($format);
}
