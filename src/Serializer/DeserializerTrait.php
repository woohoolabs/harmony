<?php
namespace WoohooLabs\Harmony\Serializer;

trait DeserializerTrait
{
    /**
     * @return array
     */
    abstract public function getDeserializableFormats();

    /**
     * @param string $format
     * @return bool
     */
    public function isFormatDeserializable($format)
    {
        return in_array($format, $this->getDeserializableFormats());
    }
}
