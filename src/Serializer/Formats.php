<?php
namespace WoohooLabs\Harmony\Serializer;

use WoohooLabs\Harmony\Request\MimeTypes;

class Formats
{
    const JSON= "json";
    const XML= "xml";
    const YML= "yml";
    const HTML= "html";
    const PLAIN= "plain";
    const FORM= "form";

    /**
     * @param string $mime
     * @return string|null
     */
    public static function convertMimeTypeToFormat($mime)
    {
        $formats= [
            MimeTypes::JSON => self::JSON,
            MimeTypes::XML => self::XML,
            MimeTypes::JSON => self::JSON
        ];

        return isset($formats[$mime]) ? $formats[$mime] : null;
    }

    /**
     * @param string $format
     * @return string|null
     */
    public static function convertFormatToMimeType($format)
    {
        $mimeTypes= [
            self::JSON => MimeTypes::JSON,
            self::XML => MimeTypes::XML,
            self::JSON => MimeTypes::JSON
        ];

        return isset($mimeTypes[$format]) ? $mimeTypes[$format] : null;
    }
}
