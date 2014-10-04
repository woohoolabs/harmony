<?php
namespace WoohooLabs\ApiFramework\Serializer\Serializer;

interface SerializerInterface
{
    /**
     * @param mixed $data
     * @param string $mediaType
     * @return string
     * @throws \WoohooLabs\ApiFramework\Serializer\MediaTypeNotSupportedException
     * @throws \WoohooLabs\ApiFramework\Serializer\SerializerException
     */
    public function serialize($data, $mediaType);
}
