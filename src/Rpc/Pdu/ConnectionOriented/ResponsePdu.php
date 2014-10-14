<?php

namespace Aztech\Rpc\Pdu\ConnectionOriented;

use Aztech\Rpc\Pdu\ConnectionOrientedPdu;
use Aztech\Rpc\PduType;
use Aztech\Rpc\ProtocolDataUnitVisitor;
use Aztech\Rpc\DataRepresentationFormat;

class ResponsePdu extends ConnectionOrientedPdu
{

    private $body;

    public function __construct(DataRepresentationFormat $format = null)
    {
        parent::__construct(PduType::RESPONSE, null, $format);
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function accept(ProtocolDataUnitVisitor $visitor)
    {
        throw new \BadMethodCallException();
    }
}
