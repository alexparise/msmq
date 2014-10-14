<?php

namespace Aztech\Rpc\Auth;

use Aztech\Rpc\AuthenticationVerifier;
use Aztech\Rpc\PduFieldCollection;
use Aztech\Net\DataTypes;
use Aztech\Rpc\ProtocolDataUnit;
use Aztech\Ntlm\Session;

class NtlmVerifier implements AuthenticationVerifier
{

    private $context;

    private $session;

    public function __construct(AuthenticationContext $context, Session $session)
    {
        $this->context = $context;
        $this->session = $session;
    }


    public function getSize($padding)
    {
        return 0;
        return $this->getContent()->getSize() + $this->getHeaders($padding)->getSize();
    }

    public function getHeaders($padding)
    {
        $headers = new PduFieldCollection();
        return $headers;

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
        return $fields;

        $fields->addField(DataTypes::UINT32, 0x0001);
        $fields->addField(DataTypes::BYTES, $this->session->getKey());

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
