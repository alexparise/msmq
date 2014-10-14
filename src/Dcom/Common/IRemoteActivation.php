<?php

namespace Aztech\Dcom\Common;

use Aztech\Dcom\DcomInterface;
use Rhumsaa\Uuid\Uuid;

class IRemoteActivation extends DcomInterface
{

    const IID = 'B84A9F4D1C7DCF11861E0020AF6E7C57';
    
    protected function getIid()
    {
        return Uuid::fromBytes(hex2bin(self::IID));
    }
}