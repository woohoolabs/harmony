<?php
namespace WoohooLabs\ApiFramework\Serializer;

abstract class AbstractSerializer
{
    /**
     * @var array
     */
    protected $supportedMediaTypes= [];

    /**
     * @param string $mediaType
     * @return boolean
     */
    protected function isMediaTypeSupported($mediaType)
    {
        return isset($this->supportedMediaTypes[$mediaType]);
    }

    /**
     * @param string $mediaType
     * @return string mixed
     */
    protected function getFormat($mediaType) {
        return $this->supportedMediaTypes[$mediaType];
    }
}
