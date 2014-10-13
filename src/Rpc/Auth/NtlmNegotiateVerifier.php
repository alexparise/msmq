<?php

namespace Aztech\Rpc\Auth;

use Aztech\Ntlm\Client;
use Aztech\Rpc\AuthenticationVerifier;
use Aztech\Rpc\ProtocolDataUnit;
use Aztech\Rpc\PduFieldCollection;
use Aztech\Net\DataTypes;

class NtlmNegotiateVerifier implements AuthenticationVerifier
{
    
    private $client;
    
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    
    public function getContent()
    {
        $collection = new PduFieldCollection();
        
        $collection->addField(DataTypes::BYTES, $this->client->getNegotiatePacket());

        return $collection;
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