<?php

namespace Aztech\Rpc\Parser\ConnectionOriented;

use Aztech\Rpc\RawProtocolDataUnit;
use Aztech\Net\Buffer\BufferReader;
use Aztech\Net\ByteOrder;
use Aztech\Rpc\Pdu\ConnectionOriented\BindNackPdu;

class BindNackPduParser extends AbstractParser
{
    public function parse(RawProtocolDataUnit $rawPdu)
    {
        $reader = new BufferReader($rawPdu->getBytes(), ByteOrder::LITTLE_ENDIAN);
        
        $reader->skip(self::ALIGN_FORMAT);
        $format = $this->parseDataRepresentationFormat($reader);
        
        $pdu = new BindNackPdu($format);
        
        $this->parseCallId($reader, $pdu);
        $this->parseReason($reader, $pdu);
        
        return $pdu;
    }
    
    private function parseReason($reader, $pdu)
    {
        $reason = $reader->readUInt16();
        
        $pdu->setReason($reason);
    }
}