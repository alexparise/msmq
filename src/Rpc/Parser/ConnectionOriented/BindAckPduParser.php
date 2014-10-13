<?php

namespace Aztech\Rpc\Parser\ConnectionOriented;

use Aztech\Rpc\PduParser;
use Aztech\Rpc\RawProtocolDataUnit;
use Aztech\Rpc\Pdu\ConnectionOriented\BindAckPdu;
use Aztech\Net\Buffer\BufferReader;
use Aztech\Net\DataTypes;
use Aztech\Util\Text;
use Rhumsaa\Uuid\Uuid;
use Aztech\Rpc\Pdu\ConnectionOriented\BindContextResultItem;

class BindAckPduParser extends AbstractParser
{

    public function parse(RawProtocolDataUnit $rawPdu)
    {
        $reader = new BufferReader($rawPdu->getBytes());

        $reader->skip(self::ALIGN_FORMAT);
        $format = $this->parseDataRepresentationFormat($reader);

        $pdu = new BindAckPdu($format);

        $reader->skip(4);
        $pdu->setCallId($reader->readUInt32());

        $reader->skip(DataTypes::INT16_SZ * 2);
        $pdu->setAssociationGroupId($reader->readUInt32());

        $secondaryAddressLen = $reader->readUInt16();
        $secondaryAddress = (int) $reader->read($secondaryAddressLen - 1);

        $pdu->setSecondaryAddress($secondaryAddress);

        // Move to end of 4-octet alignment
        while ($reader->getReadByteCount() % 4 != 0) {
            $reader->skip(1);
        }

        $resultCount = $reader->readUInt32();
        $results = [];

        for ($i = 0; $i < $resultCount; $i++) {
            $ackResult = $reader->readUInt16();
            $ackReason = ($ackResult == 0) ? '' : $reader->readUInt16();
            $syntax = Uuid::fromBytes(($reader->read(16)));
            $syntaxVersion = $reader->readUInt32();

            $pdu->addResult(new BindContextResultItem($ackResult, $ackReason, $syntax, $syntaxVersion));
        }

        return $pdu;
    }
}
