<?php
namespace WoohooLabs\ApiFramework\Serializer\Deserializer;

interface DeserializerInterface
{
    /**
     * @param string $data
     * @param string $mediaType
     * @param boolean $isArray
     * @return array|Object
     * @throws \WoohooLabs\ApiFramework\Serializer\MediaTypeNotSupportedException
     * @throws \WoohooLabs\ApiFramework\Serializer\SerializerException
     */
    public function deserialize($data, $mediaType, $isArray = true);
}
