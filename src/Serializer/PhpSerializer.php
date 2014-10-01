<?php
namespace WoohooLabs\ApiFramework\Serializer;

use WoohooLabs\ApiFramework\Request\MimeTypes;

class PhpSerializer implements SerializerInterface
{
    /**
     * @var array
     */
    private $mediaTypes= [
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

    /**
     * @param string $data
     * @param string $mediaType
     * @param string|null $type
     * @return Object|array
     * @throws \WoohooLabs\ApiFramework\Serializer\MediaTypeNotSupportedException
     * @throws \WoohooLabs\ApiFramework\Serializer\SerializerException
     */
    public function deserialize($data, $mediaType, $type = null)
    {
        if ($this->isMediaTypeSupported($mediaType) !== true) {
            throw new MediaTypeNotSupportedException();
        }

        return json_decode($data, $type == null);
    }

    /**
     * @param string $mediaType
     * @return boolean
     */
    protected function isMediaTypeSupported($mediaType)
    {
        return isset($this->mediaTypes[$mediaType]);
    }

    /**
     * @param string $mediaType
     * @return string mixed
     */
    protected function getFormat($mediaType) {
        return $this->mediaTypes[$mediaType];
    }
}
