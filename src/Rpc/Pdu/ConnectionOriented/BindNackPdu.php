<?php

namespace Aztech\Rpc\Pdu\ConnectionOriented;

use Aztech\Rpc\Pdu\ConnectionOrientedPdu;
use Aztech\Net\DataTypes;
use Aztech\Rpc\DataRepresentationFormat;
use Aztech\Rpc\ProtocolDataUnit;
use Aztech\Rpc\PduType;
use Aztech\Rpc\PduFieldCollection;
use Aztech\Rpc\ProtocolDataUnitVisitor;

class BindNackPdu extends ConnectionOrientedPdu
{

    private $reason;
    
    public function __construct(DataRepresentationFormat $format = null)
    {
        parent::__construct(PduType::BIND_ACK, null, $format);
    }

    public function accept(ProtocolDataUnitVisitor $visitor)
    {
        return $visitor->visitBindAck($this);
    }
    
    public function getReason()
    {
        return $this->reason;
    }

    public function setReason($reason)
    {
        $this->reason = $reason;
    }
}
