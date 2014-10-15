<?php

namespace Aztech\Dcom;

use Rhumsaa\Uuid\Uuid;
use Aztech\Util\Guid;

class Handle
{
    public static function null()
    {
        return new Handle(0, Guid::null());
    }

    private $attributes;

    private $uuid;

    public function __construct($attributes, Uuid $uuid)
    {
        $this->attributes = $attributes;
        $this->uuid = $uuid;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getUuid()
    {
        return $this->uuid;
    }
}
