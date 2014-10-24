<?php
namespace WoohooLabs\ApiFramework\Serializer;

interface SerializerInterface
{
    /**
     * @param mixed $data
     * @param string $format
     * @return string
     * @throws \WoohooLabs\ApiFramework\Serializer\FormatNotSupportedException
     * @throws \WoohooLabs\ApiFramework\Serializer\SerializerException
     */
    public function serialize($data, $format);

    /**
     * @param string $format
     * @return boolean
     */
    public function isFormatSerializable($format);
}
