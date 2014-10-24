<?php
namespace WoohooLabs\ApiFramework\Serializer;

trait SerializerTrait
{
    /**
     * @return array
     */
    abstract public function getSerializableFormats();

    /**
     * @param string $format
     * @return boolean
     */
    public function isFormatSerializable($format)
    {
        return in_array($format, $this->getSerializableFormats());
    }
}
