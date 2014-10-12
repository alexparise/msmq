<?php

namespace Aztech\Rpc\Pdu;

use Aztech\Net\DataTypes;
use Aztech\Rpc\PduFieldCollection;
use Aztech\Rpc\ProtocolDataUnit;
use Aztech\Rpc\Rpc;
use Aztech\Rpc\DataRepresentationFormat;

abstract class ConnectionOrientedPdu implements ProtocolDataUnit
{

    const AUTH_HEADER_SIZE = 8;

    const HEADER_SIZE = 16;

    const RESP_HEADER_SIZE = 8;

    const FRAG_SZ = 5840;

    private $flags;

    private $format;

    private $packetType;

    public function __construct($packetType, DataRepresentationFormat $format = null)
    {
        $this->format = $format ?: new DataRepresentationFormat();
        $this->packetType = $packetType;
        $this->flags = ProtocolDataUnit::PFC_FIRST_FRAG | ProtocolDataUnit::PFC_LAST_FRAG;
    }

    abstract public function getFragmentLength();

    abstract public function getAuthLength();

    abstract public function getCallId();

    public function getFormat()
    {
        return $this->format;
    }

    public function getHeaders()
    {
        $headers = new PduFieldCollection();

        $headers->add(DataTypes::UINT8, Rpc::VERSION_MAJOR);
        $headers->add(DataTypes::UINT8, Rpc::VERSION_MINOR);
        $headers->add(DataTypes::UINT8, $this->packetType);
        $headers->add(DataTypes::UINT8, $this->flags);
        $headers->add(DataTypes::UINT32, $this->format->getValue());
        $headers->add(DataTypes::UINT16, $this->getFragmentLength());
        $headers->add(DataTypes::UINT16, $this->getAuthLength());
        $headers->add(DataTypes::UINT32, $this->getCallId());

        return $headers;
    }
}
