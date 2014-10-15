<?php

namespace Aztech\Util;

use Rhumsaa\Uuid\Uuid;

class Guid
{
    /**
     * Parses Windows string GUID's into a UUID object whose bits are properly flipped.
     * @param int $string
     */
    public static function fromString($string)
    {
        $matches = [];

        if (! preg_match('/^\{?([A-Z0-9]{8})-?([A-Z0-9]{4})-?([A-Z0-9]{4})-?([A-Z0-9]{4})-?([A-Z0-9]{12})\}?$/', strtoupper($string), $matches)) {
            throw new \InvalidArgumentException('Not a GUID.');
        }

        $matches[1] =bin2hex(pack('V', hexdec($matches[1])));
        $matches[2] =bin2hex(pack('v', hexdec($matches[2])));
        $matches[3] =bin2hex(pack('v', hexdec($matches[3])));

        return Uuid::fromString(strtoupper(implode('-', array_slice($matches, 1))));
    }

    public static function null()
    {
        return self::fromString('{00000000-0000-0000-0000-000000000000}');
    }
}
