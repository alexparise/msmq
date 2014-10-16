<?php

namespace Aztech\Rpc\Pdu\ConnectionOriented;

use Aztech\Rpc\Pdu\ConnectionOrientedPdu;
use Aztech\Net\DataTypes;
use Aztech\Rpc\DataRepresentationFormat;
use Aztech\Rpc\ProtocolDataUnit;
use Aztech\Rpc\PduType;
use Aztech\Rpc\Auth\AuthenticationVerifier;
use Aztech\Rpc\PduFieldCollection;
use Aztech\Rpc\ProtocolDataUnitVisitor;

class BindResponsePdu extends ConnectionOrientedPdu
{

    public function __construct(AuthenticationVerifier $verifier, DataRepresentationFormat $format = null)
    {
        parent::__construct(PduType::BIND_RESP, $verifier, $format);
    }

    public function accept(ProtocolDataUnitVisitor $visitor)
    {
        return $visitor->visitBindResponse($this);
    }
}
