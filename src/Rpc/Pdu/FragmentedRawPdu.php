<?php

namespace Aztech\Rpc\Pdu;

use Aztech\Rpc\RawProtocolDataUnit;
use Aztech\Rpc\Pdu\RawConnectionOrientedPdu;

class FragmentedRawPdu implements RawProtocolDataUnit
{
    private $pdus;

    /**
     *
     * @var RawProtocolDataUnit
     */
    private $pdu;

    public function __construct(array $pdus)
    {
        if (empty($pdus)) {
            throw new \InvalidArgumentException('PDU array must contain at least one RawProtocolDataUnit instance.');
        }

        foreach ($pdus as $pdu) {
            if (! ($pdu instanceof RawProtocolDataUnit)) {
                throw new \InvalidArgumentException('PDU array must only contain RawProtocolDataUnit instances.');
            }
        }

        $this->pdus = $pdus;

        if (count($pdus) > 1) {
            $pdus = $this->mergePdus($pdus);
        }

        $this->pdu = reset($pdus);
    }

    public function getBytes()
    {
        return $this->pdu->getBytes();
    }

    public function getPacketSize()
    {
        return $this->pdu->getPacketSize();
    }

    public function getType()
    {
        return $this->pdu->getType();
    }

    public function getVersion()
    {
        return $this->pdu->getVersion();
    }

    public function isLastFragment()
    {
        return true;
    }

    /**
     *
     * @param RawProtocolDataUnit[] $pdus
     */
    private function mergePdus(array $pdus)
    {
        throw new \BadMethodCallException('Fragmented PDUs not yet implemented.');
    }
}
