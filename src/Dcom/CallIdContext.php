<?php

namespace Aztech\Dcom;

class CallIdContext
{
    private static $counter = 0;

    public static function getNext()
    {
        return ++self::$counter;
    }
}
