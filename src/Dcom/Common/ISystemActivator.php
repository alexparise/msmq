<?php

namespace Aztech\Dcom\Common;

use Aztech\Dcom\DcomInterface;
use Rhumsaa\Uuid\Uuid;
use Aztech\Rpc\TransferSyntax;
use Aztech\Rpc\Client;

class ISystemActivator extends CommonInterface
{
    const IID = 'A001000000000000C000000000000046';

    protected function getIid()
    {
        return Uuid::fromBytes(hex2bin(self::IID));
    }

    protected function getSyntaxes()
    {
        return [ TransferSyntax::getNdr() ];
    }

    public function createObject(Uuid $clsId)
    {
        echo 'Creating object';
        return $this->execute($this->client, 0x03);
    }
}
