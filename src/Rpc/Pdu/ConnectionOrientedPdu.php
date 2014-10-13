<?php

namespace Aztech\Rpc\Pdu;

use Aztech\Net\DataTypes;
use Aztech\Rpc\PduFieldCollection;
use Aztech\Rpc\ProtocolDataUnit;
use Aztech\Rpc\Rpc;
use Aztech\Rpc\DataRepresentationFormat;
use Aztech\Rpc\AuthenticationVerifier;

abstract class ConnectionOrientedPdu implements ProtocolDataUnit
{

    private $callId = 1;

    private $flags;

    private $format;

    private $packetType;

    private $verifier;

    public function __construct($packetType, AuthenticationVerifier $verifier = null, DataRepresentationFormat $format = null)
    {
        $this->format = $format ?: new DataRepresentationFormat();
        $this->packetType = $packetType;
        $this->flags = ProtocolDataUnit::PFC_FIRST_FRAG | ProtocolDataUnit::PFC_LAST_FRAG;
        $this->verifier = $verifier;
    }

    public function setCallId($id)
    {
        $this->callId = $id;
    }

    public function getCallId()
    {
        return $this->callId;
    }

    public function getFlags()
    {
        return $this->flags;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function getType()
    {
        return $this->packetType;
    }

    /**
     *
     * @return AuthenticationVerifier|null
     */
    public function getVerifier()
    {
        return $this->verifier;
    }
}
