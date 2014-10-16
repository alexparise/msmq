<?php

namespace Aztech\Dcom;

class Pointer
{
    private static $counter = 0;

    private $id;

    private $value;

    public function __construct($value)
    {
        $this->id = ++self::$counter;
        $this->value = $value;
    }

    public function getRefId()
    {
        return $this->id;
    }

    public function getValue()
    {
        return $this->value;
    }
}
