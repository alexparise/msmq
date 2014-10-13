<?php

namespace Aztech\Rpc\Pdu;

use Aztech\Rpc\RawProtocolDataUnit;
use Aztech\Rpc\ProtocolDataUnit;

class RawConnectionOrientedPdu implements RawProtocolDataUnit
{
    private $bytes = null;

    private $flags = 0x00;

    private $packetSize;

    private $version;

    public function __construct($bytes, $packetSize, $flags, $version, $type)
    {
        $this->packetSize = $packetSize;
        $this->bytes = $bytes;
        $this->flags = $flags;
        $this->version = $version;
        $this->type = $type;
    }

    public function getBytes()
    {
        return $this->bytes;
    }

    public function getPacketSize()
    {
        return $this->packetSize;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function isLastFragment()
    {
        return (bool)($this->flags & ProtocolDataUnit::PFC_LAST_FRAG);
    }
}
