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

class FaultPdu extends ConnectionOrientedPdu
{

    private $errorCode = 0;

    public function __construct($errorCode, DataRepresentationFormat $format = null)
    {
        parent::__construct(PduType::FAULT, null, $format);

        $this->errorCode = $errorCode;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function accept(ProtocolDataUnitVisitor $visitor)
    {
        throw new \BadMethodCallException();
    }
}
