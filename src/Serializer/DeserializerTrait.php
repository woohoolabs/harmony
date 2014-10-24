<?php
namespace WoohooLabs\ApiFramework\Serializer;

trait DeserializerTrait
{
    /**
     * @return array
     */
    abstract public function getDeserializableFormats();

    /**
     * @param string $format
     * @return boolean
     */
    public function isFormatDeserializable($format)
    {
        return in_array($format, $this->getDeserializableFormats());
    }
}
