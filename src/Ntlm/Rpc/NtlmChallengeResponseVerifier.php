<?php

namespace Aztech\Ntlm\Rpc;

use Aztech\Rpc\Auth\AuthenticationVerifier;
use Aztech\Rpc\ProtocolDataUnit;
use Aztech\Rpc\PduFieldCollection;
use Aztech\Net\DataTypes;
use Aztech\Ntlm\Message\ChallengeMessage;
use Aztech\Ntlm\Client;
use Aztech\Rpc\Auth\AuthenticationContext;

class NtlmChallengeResponseVerifier implements AuthenticationVerifier
{

    private $challengeResponse;

    private $context;

    public function __construct(AuthenticationContext $context, $challengeResponse)
    {
        $this->challengeResponse = $challengeResponse;
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

        $collection->addField(DataTypes::BYTES, $this->challengeResponse);

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
