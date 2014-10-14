<?php

namespace Aztech\Rpc;

use Rhumsaa\Uuid\Uuid;

class TransferSyntax
{
    const NDR = '045D888AEB1CC9119FE808002B104860';

    const NDR_VERSION = 0x02;

    const NEGOTIATE = '2C1CB76C129840450300000000000000';

    const NEGOTIATE_VERSION = 0x02;

    public static function getNdr()
    {
        return new Syntax(self::NDR, self::NDR_VERSION);
    }

    public static function getNegotiate()
    {
        return new Syntax(self::NEGOTIATE, self::NEGOTIATE_VERSION);
    }
}
