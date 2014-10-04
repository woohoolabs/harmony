<?php
namespace WoohooLabs\ApiFramework\Serializer\Serializer;

class NaiveSerializer implements SerializerInterface
{
    /**
     * @param array|Object $data
     * @param string $mediaType
     * @return string
     */
    public function serialize($data, $mediaType)
    {
        return $data;
    }
}
