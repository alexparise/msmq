<?php

namespace Aztech\Rpc\Auth;

use Aztech\Rpc\AuthenticationVerifier;
use Aztech\Rpc\PduFieldCollection;
use Aztech\Net\DataTypes;
use Aztech\Rpc\ProtocolDataUnit;

class NtlmVerifier implements AuthenticationVerifier
{

    private $context;
    
    private $signature;
    
    public function __construct(AuthenticationContext $context, $signature)
    {
        $this->context = $context;
        $this->signature = $signature;
    }
    
    
    public function getSize($padding)
    {
        return $this->getContent()->getSize() + $this->getHeaders($padding)->getSize();
    }
    
    public function getHeaders($padding)
    {
        $headers = new PduFieldCollection();
    
        $headers->addField(DataTypes::UINT8, $this->context->getAuthType());
        $headers->addField(DataTypes::UINT8, $this->context->getAuthLevel());
        $headers->addField(DataTypes::UINT8, $padding);
        $headers->addField(DataTypes::UINT8, 0);
        $headers->addField(DataTypes::UINT32, $this->context->getContextId());
    
        return $headers;
    }
    
    public function getContent()
    {
        $fields = new PduFieldCollection();
        
        $fields->addField(DataTypes::UINT32, 0x0001);
        $fields->addField(DataTypes::BYTES, $this->signature);
        
        return $fields;
    }
    
    public function seal(ProtocolDataUnit $pdu)
    {
        return $pdu;
    }
    
    public function sign(ProtocolDataUnit $pdu)
    {
        return $pdu;
    }
}
