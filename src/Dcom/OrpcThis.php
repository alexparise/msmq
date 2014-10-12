<?php

namespace Aztech\Dcom;

use Rhumsaa\Uuid\Uuid;
use Aztech\Net\PacketWriter;
use Aztech\Net\ByteOrder;

class OrpcThis
{
    private $major = 5;
    
    private $minor = 1;
    
    private $flags = 0x00;
    
    private $reserved = 0x00;
    
    private $cid;
    
    public function __construct(Uuid $cid)
    {
        $this->cid = $cid;        
    }
    
    public function getContent()
    {
        $writer = new PacketWriter();
        
        $writer->writeUInt16($this->major, ByteOrder::LITTLE_ENDIAN);
        $writer->writeUInt16($this->minor, ByteOrder::LITTLE_ENDIAN);
        $writer->writeUInt32($this->flags, ByteOrder::LITTLE_ENDIAN);
        $writer->writeUInt32($this->reserved, ByteOrder::LITTLE_ENDIAN);
        $writer->write($this->cid->getBytes());
        $writer->writeUInt32(0, ByteOrder::LITTLE_ENDIAN);
        
        return $writer->getBuffer();
    }
}