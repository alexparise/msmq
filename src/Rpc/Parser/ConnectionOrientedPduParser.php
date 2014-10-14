<?php

namespace Aztech\Rpc\Parser;

use Aztech\Rpc\PduParser;
use Aztech\Rpc\RawProtocolDataUnit;
use Aztech\Rpc\ProtocolDataUnit;
use Aztech\Rpc\PduType;
use Aztech\Rpc\Parser\ConnectionOriented\BindAckPduParser;
use Aztech\Rpc\Parser\ConnectionOriented\BindNackPduParser;
use Aztech\Rpc\Parser\ConnectionOriented\ResponsePduParser;

class ConnectionOrientedPduParser implements PduParser
{

    public function parse(RawProtocolDataUnit $rawPdu)
    {
        switch ($rawPdu->getType()) {
            case PduType::BIND_ACK:
                return (new BindAckPduParser())->parse($rawPdu);
            case PduType::BIND_NACK:
                return (new BindNackPduParser())->parse($rawPdu);
            case PduType::RESPONSE:
                return (new ResponsePduParser())->parse($rawPdu);
        }
    }
}
