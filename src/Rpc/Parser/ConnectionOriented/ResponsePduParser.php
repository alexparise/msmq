<?php

namespace Aztech\Rpc\Parser\ConnectionOriented;

use Aztech\Rpc\RawProtocolDataUnit;
use Aztech\Rpc\Pdu\ConnectionOriented\ResponsePdu;
use Aztech\Net\Buffer\BufferReader;

class ResponsePduParser extends AbstractParser
{

    public function parse(RawProtocolDataUnit $rawPdu)
    {
        $reader = new BufferReader($rawPdu->getBytes());

        $format = $this->parseDataRepresentationFormat($reader);

        $reader->seek(16);
        $bodySize = $reader->readUInt32();
        $contextId = $reader->readUInt16();

        $reader->readUInt8();
        $reader->readUInt8();
        $body = $reader->read($bodySize);

        $response = new ResponsePdu($format);
        $response->setBody($body);

        return $response;
    }
}
