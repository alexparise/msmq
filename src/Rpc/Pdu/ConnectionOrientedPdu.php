<?php

namespace Aztech\Rpc\Pdu;

use Aztech\Rpc\ProtocolDataUnit;
use Aztech\Rpc\WriteVisitor;

class ConnectionOrientedPdu implements ProtocolDataUnit
{

    private $header = null;

    private $body = null;

    public function __construct($type)
    {
        if (! $this->validateType($type)) {
            throw new \InvalidArgumentException('Type is not connection oriented.');
        }

        $this->type = $type;
    }

    private function validateType($type)
    {
        switch ($type) {
            case ProtocolDataUnit::TYPE_REQUEST:
            case ProtocolDataUnit::TYPE_RESPONSE:
            case ProtocolDataUnit::TYPE_FAULT:
            case $type >= ProtocolDataUnit::TYPE_BIND:
            case $type <= ProtocolDataUnit::TYPE_ORPHANED:
                return true;
        }

        return false;
    }

    public function getType()
    {
        return $this->type;
    }

    public function accept(WriteVisitor $writer)
    {
        $writer->visitPdu($this);

        $this->header->accept($writer);
        $this->body->accept($writer);
    }
}
