<?php
namespace WoohooLabs\ApiFramework\Serializer\Serializer;

use WoohooLabs\ApiFramework\Request\MimeTypes;
use WoohooLabs\ApiFramework\Serializer\AbstractSerializer;
use WoohooLabs\ApiFramework\Serializer\MediaTypeNotSupportedException;

class PhpSerializer extends AbstractSerializer implements SerializerInterface
{
    /**
     * @var array
     */
    protected $supportedMediaTypes= [
        MimeTypes::JSON => "json",
    ];

    /**
     * @param array|Object $data
     * @param string $mediaType
     * @return string
     * @throws \WoohooLabs\ApiFramework\Serializer\MediaTypeNotSupportedException
     * @throws \WoohooLabs\ApiFramework\Serializer\SerializerException
     */
    public function serialize($data, $mediaType)
    {
        if ($this->isMediaTypeSupported($mediaType) !== true) {
            throw new MediaTypeNotSupportedException();
        }

        return json_encode($data);
    }
}
