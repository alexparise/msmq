<?php

namespace Aztech\Rpc\Pdu\ConnectionOriented;

use Aztech\Rpc\Pdu\ConnectionOrientedPdu;
use Aztech\Net\DataTypes;
use Aztech\Rpc\DataRepresentationFormat;
use Aztech\Rpc\ProtocolDataUnit;
use Aztech\Rpc\PduType;
use Aztech\Rpc\AuthenticationVerifier;
use Aztech\Rpc\PduFieldCollection;
use Aztech\Rpc\ProtocolDataUnitVisitor;

class BindPdu extends ConnectionOrientedPdu
{

    private $context;

    private $assocationGroupId = 0;

    public function __construct(BindContext $context, AuthenticationVerifier $verifier, DataRepresentationFormat $format = null)
    {
        parent::__construct(PduType::BIND, $verifier, $format);

        $this->context = $context;
        $this->verifier = $verifier;
    }

    public function getAssociationGroupId()
    {
        return $this->assocationGroupId;
    }

    public function setAssociationGroupId($id)
    {
        $this->assocationGroupId = $id;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function accept(ProtocolDataUnitVisitor $visitor)
    {
        return $visitor->visitBind($this);
    }
}
