<?php
namespace WoohooLabs\ApiFramework\Serializer;

interface SerializerInterface
{
    /**
     * @param array|Object $data
     * @param string $mediaType
     * @return string
     * @throws \WoohooLabs\ApiFramework\Serializer\MediaTypeNotSupportedException
     * @throws \WoohooLabs\ApiFramework\Serializer\SerializerException
     */
    public function serialize($data, $mediaType);

    /**
     * @param string $data
     * @param string $mediaType
     * @param string|null $type
     * @return Object|array
     * @throws \WoohooLabs\ApiFramework\Serializer\MediaTypeNotSupportedException
     * @throws \WoohooLabs\ApiFramework\Serializer\SerializerException
     */
    public function deserialize($data, $mediaType, $type = null);
}
