<?php

namespace Aztech\Rpc\Auth;

use Aztech\Ntlm\Client;
use Aztech\Rpc\AuthenticationVerifier;
use Aztech\Rpc\ProtocolDataUnit;
use Aztech\Rpc\PduFieldCollection;
use Aztech\Net\DataTypes;

class NtlmNegotiateVerifier implements AuthenticationVerifier
{

    private $context;

    private $negotiate;

    public function __construct(AuthenticationContext $context, $negotiatePacket)
    {
        $this->context = $context;
        $this->negotiate = $negotiatePacket;
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

        $collection->addField(DataTypes::BYTES, $this->negotiate);

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
