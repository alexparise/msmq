<?php

namespace Aztech\Dcom\Common;

use Aztech\Dcom\DcomInterface;
use Rhumsaa\Uuid\Uuid;
use Aztech\Dcom\TransferSyntax;
use Aztech\Rpc\Client;

class ISystemActivator extends CommonInterface
{
    const IID = 'A001000000000000C000000000000046';
    
    protected function getIid()
    {
        return Uuid::fromBytes(hex2bin(self::IID));
    }
    
    public function createObject(Uuid $clsId)
    {
        return $this->execute($this->client, 0x03, $clsId);
    }
}