<?php

namespace Aztech\Rpc\Request;

use Aztech\Rpc\Request;
use Aztech\Net\PacketWriter;
use Aztech\Rpc\Rpc;
use Aztech\Net\ByteOrder;
use Aztech\Ntlm\Message\ChallengeMessage;

class BindResponseRequest implements Request
{
    
    public function getContent()
    {
        $packet = new PacketWriter();
        
        $packet->writeUInt16(Rpc::FRAG_SZ, ByteOrder::LITTLE_ENDIAN);
        $packet->writeUInt16(Rpc::FRAG_SZ, ByteOrder::LITTLE_ENDIAN);
        
        return $packet->getBuffer();
    }
    
    public function getFlags()
    {
        return 0;
    }
    
    public function getType()
    {
        return Rpc::BIND_RESP;
    }
}