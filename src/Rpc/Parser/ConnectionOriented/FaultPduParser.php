<?php

namespace Aztech\Rpc\Parser\ConnectionOriented;

use Aztech\Rpc\RawProtocolDataUnit;
use Aztech\Net\Buffer\BufferReader;
use Aztech\Net\ByteOrder;
use Aztech\Rpc\Pdu\ConnectionOriented\BindNackPdu;
use Aztech\Rpc\Pdu\ConnectionOriented\FaultPdu;
use Aztech\Net\DataTypes;

class FaultPduParser extends AbstractParser
{
    public function parse(RawProtocolDataUnit $rawPdu)
    {
        $reader = new BufferReader($rawPdu->getBytes());

        $reader->skip(self::ALIGN_FORMAT);
        $format = $this->parseDataRepresentationFormat($reader);

        $reader->seek(24);
        $errorCode = $reader->readUInt32();

        $pdu = new FaultPdu($errorCode, $format);

        $this->parseCallId($reader, $pdu);

        return $pdu;
    }
}
