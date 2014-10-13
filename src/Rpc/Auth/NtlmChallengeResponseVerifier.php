<?php

namespace Aztech\Rpc\Auth;

use Aztech\Ntlm\Client;
use Aztech\Rpc\AuthenticationVerifier;
use Aztech\Rpc\ProtocolDataUnit;
use Aztech\Rpc\PduFieldCollection;
use Aztech\Net\DataTypes;
use Aztech\Ntlm\Message\ChallengeMessage;

class NtlmNegotiateVerifier implements AuthenticationVerifier
{
    
    private $client;
    
    private $challenge;
    
    public function __construct(Client $client, ChallengeMessage $challenge)
    {
        $this->client = $client;
        $this->challenge = $challenge;
    }
    
    public function hasVersion()
    {
        return false;
    }
    
    public function getVersion()
    {
        throw new \BadMethodCallException();
    }
    
    public function getContent()
    {
        $collection = new PduFieldCollection();
        
        $collection->addField(DataTypes::BYTES, $this->client->getAuthPacket($this->challenge));
        
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