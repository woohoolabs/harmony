<?php
namespace WoohooLabs\ApiFramework\Serializer\Deserializer;

use WoohooLabs\ApiFramework\Request\MimeTypes;
use WoohooLabs\ApiFramework\Serializer\AbstractSerializer;
use WoohooLabs\ApiFramework\Serializer\MediaTypeNotSupportedException;

class PhpDeserializer extends AbstractSerializer implements DeserializerInterface
{
    /**
     * @var array
     */
    protected $supportedMediaTypes= [
        MimeTypes::JSON => "json",
    ];

    /**
     * @param string $data
     * @param string $mediaType
     * @param boolean $isArray
     * @return Object|array
     * @throws \WoohooLabs\ApiFramework\Serializer\MediaTypeNotSupportedException
     */
    public function deserialize($data, $mediaType, $isArray = true)
    {
        if ($this->isMediaTypeSupported($mediaType) !== true) {
            throw new MediaTypeNotSupportedException();
        }

        return json_decode($data, $isArray);
    }
}
