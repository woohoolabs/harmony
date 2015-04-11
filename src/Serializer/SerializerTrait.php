<?php
namespace WoohooLabs\Harmony\Serializer;

trait SerializerTrait
{
    /**
     * @return array
     */
    abstract public function getSerializableFormats();

    /**
     * @param string $format
     * @return bool
     */
    public function isFormatSerializable($format)
    {
        return in_array($format, $this->getSerializableFormats());
    }
}
