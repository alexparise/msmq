<?php

namespace Aztech\Dcom\Common;

use Aztech\Dcom\DcomInterface;
use Rhumsaa\Uuid\Uuid;
use Aztech\Rpc\TransferSyntax;
use Aztech\Rpc\Client;
use Aztech\Dcom\Marshalling\MarshalledBuffer;
use Aztech\Dcom\Marshalling\UnmarshallingBuffer;

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

    public function remoteGetClassObject(Uuid $clsId)
    {
        $in = new MarshalledBuffer();
        $out = new UnmarshallingBuffer();
        
        $writer = $in->getWriter();
        
        $writer->write($this->getOrpcThis()->getBytes());
        $writer->write($clsId->getBytes());
        $writer->writeUInt32(0);
        
        return $this->execute($this->client, 0x03, $in, $out);
    }
    
    public function remoteCreateInstance($pUnkOuter, $pActProperties)
    {
        
    }
}
