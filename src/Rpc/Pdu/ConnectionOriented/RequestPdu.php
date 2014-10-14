<?php

namespace Aztech\Rpc\Pdu\ConnectionOriented;

use Aztech\Rpc\Pdu\ConnectionOrientedPdu;
use Rhumsaa\Uuid\Uuid;
use Aztech\Rpc\AuthenticationVerifier;
use Aztech\Rpc\DataRepresentationFormat;
use Aztech\Rpc\PduType;
use Aztech\Rpc\ProtocolDataUnitVisitor;
use Aztech\Rpc\ProtocolDataUnit;

class RequestPdu extends ConnectionOrientedPdu
{
    
    private $contextId = 0x00;
    
    private $opNum = 0x00;
    
    private $object;
    
    private $body;
    
    public function __construct(Uuid $object = null, $opNum, AuthenticationVerifier $verifier, DataRepresentationFormat $format = null)
    {
        parent::__construct(PduType::REQUEST, $verifier, $format);
        
        $this->object = $object;
        $this->opNum = $opNum;
    }
    
    public function getFlags()
    {
        $flags = parent::getFlags();
        
        if ($this->object) {
            $flags |= ProtocolDataUnit::PFC_OBJECT_UUID;
        }
        
        return $flags;
    }
    
    public function getBody()
    {
        return $this->body;
    }
    
    public function setBody($body)
    {
        $this->body = $body;
    }
    
    public function getContextId()
    {
        return $this->contextId;
    }
    
    public function setContextId($id)
    {
        $this->contextId = $id;
    }
    
    public function getOpNum()
    {
        return $this->opNum;
    }
    
    public function getObject()
    {
        return $this->object;
    }
    
    public function accept(ProtocolDataUnitVisitor $visitor)
    {
        return $visitor->visitRequest($this);
    }
}