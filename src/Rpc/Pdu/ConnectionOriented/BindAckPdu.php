<?php

namespace Aztech\Rpc\Pdu\ConnectionOriented;

use Aztech\Rpc\Pdu\ConnectionOrientedPdu;
use Aztech\Net\DataTypes;
use Aztech\Rpc\DataRepresentationFormat;
use Aztech\Rpc\ProtocolDataUnit;
use Aztech\Rpc\PduType;
use Aztech\Rpc\PduFieldCollection;
use Aztech\Rpc\ProtocolDataUnitVisitor;

class BindAckPdu extends ConnectionOrientedPdu
{

    private $associationGroupId = 0;

    private $secondaryAddress = 0;

    private $results = [];

    public function __construct(DataRepresentationFormat $format = null)
    {
        parent::__construct(PduType::BIND_ACK, null, $format);
    }

    public function accept(ProtocolDataUnitVisitor $visitor)
    {
        return $visitor->visitBindAck($this);
    }

    public function getAssociationGroupId()
    {
        return $this->associationGroupId;
    }

    public function setAssociationGroupId($id)
    {
        $this->associationGroupId = $id;
    }

    public function getSecondaryAddress()
    {
        return $this->secondaryAddress;
    }

    public function setSecondaryAddress($port)
    {
        $this->secondaryAddress = $port;
    }

    public function addResult(BindContextResultItem $result)
    {
        $this->results[] = $result;
    }

    public function getResults()
    {
        return $this->results;
    }
}
