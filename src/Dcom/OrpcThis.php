<?php

namespace Aztech\Dcom;

use Aztech\Net\ByteOrder;
use Aztech\Net\Buffer\BufferWriter;
use Rhumsaa\Uuid\Uuid;

class OrpcThis
{
    private $version;

    private $flags = 0x00;

    private $reserved = 0x00;

    private $cid;

    public function __construct(Uuid $cid, ComVersion $version = null)
    {
        $this->cid = $cid;
        $this->version = $version ?: new ComVersion(5, 0);
    }

    public function getBytes()
    {
        $writer = new BufferWriter();

        $writer->writeUInt16($this->version->getMajor(), ByteOrder::LITTLE_ENDIAN);
        $writer->writeUInt16($this->version->getMinor(), ByteOrder::LITTLE_ENDIAN);
        $writer->writeUInt32($this->flags, ByteOrder::LITTLE_ENDIAN);
        $writer->writeUInt32($this->reserved, ByteOrder::LITTLE_ENDIAN);
        $writer->write($this->cid->getBytes());
        $writer->writeUInt32(0, ByteOrder::LITTLE_ENDIAN);

        return $writer->getBuffer();
    }
}
