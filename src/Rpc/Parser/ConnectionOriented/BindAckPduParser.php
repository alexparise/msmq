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
use Aztech\Net\ByteOrder;

class BindAckPduParser extends AbstractParser
{

    public function parse(RawProtocolDataUnit $rawPdu)
    {
        $reader = new BufferReader($rawPdu->getBytes(), ByteOrder::LITTLE_ENDIAN);

        $reader->skip(self::ALIGN_FORMAT);
        $format = $this->parseDataRepresentationFormat($reader);

        $pdu = new BindAckPdu($format);

        $this->parseCallId($reader, $pdu);
        $this->parseAssocGroupId($reader, $pdu);        
        $this->parseSecondaryAddress($reader, $pdu);
        
        $this->align($reader);

        $this->parseResults($reader, $pdu);
        
        $pdu->setRawAuthData($this->parseAuth($reader, $rawPdu));

        return $pdu;
    }
    
    private function parseAssocGroupId($reader, $pdu)
    {
        $reader->skip(DataTypes::INT16_SZ * 2);
        $pdu->setAssociationGroupId($reader->readUInt32());
    }
    
    private function parseSecondaryAddress($reader, $pdu)
    {
        $secondaryAddressLen = $reader->readUInt16();
        $secondaryAddress = (int) $reader->read($secondaryAddressLen - 1);
        
        $pdu->setSecondaryAddress($secondaryAddress);
    }
    
    private function parseResults($reader, $pdu)
    {
        $resultCount = $reader->readUInt32();
        $results = [];
        
        for ($i = 0; $i < $resultCount; $i++) {
            $ackResult = $reader->readUInt16();
            $ackReason = $reader->readUInt16();
            $syntax = Uuid::fromBytes(($reader->read(16)));
            $syntaxVersion = $reader->readUInt32();
            
            $pdu->addResult(new BindContextResultItem($ackResult, $ackReason, $syntax, $syntaxVersion));
        }
    }
}
