<?php

namespace Aztech\Dcom\Common;

use Aztech\Dcom\DcomInterface;
use Rhumsaa\Uuid\Uuid;
use Aztech\Dcom\Marshalling\MarshalledBuffer;
use Aztech\Dcom\Marshalling\UnmarshallingBuffer;

class IRemoteActivation extends CommonInterface
{

    const IID = 'B84A9F4D1C7DCF11861E0020AF6E7C57';
    
    const IUNK = '00000000-0000-0000-C000-000000000046';
    
    protected function getIid()
    {
        return Uuid::fromBytes(hex2bin(self::IID));
    }
    
    public function remoteActivation(Uuid $clsid, array $iids)
    {
        $in = new MarshalledBuffer();
        $out = new UnmarshallingBuffer();
        
        $writer = $in->getWriter();
        
        $writer->write($this->getOrpcThis()->getBytes());
        
        $buffer = $in->getWriter();
        
        // DCOM
        // ClsId
        $buffer->write($clsid->getBytes());
        // OBJREF Count ???
        $buffer->writeUInt32(0);
        // ??
        $buffer->writeUInt32(0);
        // Client imp level
        $buffer->writeUInt32(0);
        // Mode
        $buffer->writeUInt32(0);
        // IID count
        $buffer->writeUInt32(count($iids));
        // ???
        $buffer->write(pack('H*', '803F140001000000'));
        
        foreach ($iids as $iid) {
            $buffer->write($iid->getBytes());
        }
        
        // RequestedProtSeq
        $buffer->writeUInt32(1);
        $buffer->writeUInt32(1);
        // Type (tcp)
        $buffer->writeUInt16(7);
        
        $response = $this->execute($this->client, 0x00, $in, $out);
    }
}