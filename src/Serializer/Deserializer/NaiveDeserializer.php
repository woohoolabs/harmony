<?php
namespace WoohooLabs\ApiFramework\Serializer\Deserializer;

class NaiveDeserializer implements DeserializerInterface
{
    /**
     * @param string $data
     * @param string $mediaType
     * @param boolean $isArray
     * @return mixed
     */
    public function deserialize($data, $mediaType, $isArray = true)
    {
        return $data;
    }
}
