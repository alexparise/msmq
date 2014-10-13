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

    private $context;

    public function __construct(Client $client, AuthenticationContext $context)
    {
        $this->client = $client;
        $this->context = $context;
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
