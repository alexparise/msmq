<?php

namespace Aztech\Rpc\Pdu;

use Aztech\Rpc\RawProtocolDataUnit;
use Aztech\Rpc\Pdu\RawConnectionOrientedPdu;
use Aztech\Util\Text;

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

    public function getFlags()
    {
        return $this->flags;
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
        /* @var $pdu RawProtocolDataUnit */
        $pdu = array_slice($pdus, 0, 1)[0];

        $bytes = $pdu->getBytes();
        $packetSize = $pdu->getPacketSize();
        $flags = $pdu->getFlags();
        $version = $pdu->getVersion();
        $type = $pdu->getType();

        foreach (array_slice($pdus, 1) as $pdu) {
            $bytes .= substr($pdu->getBytes(), 24);
            $packetSize .= min(0, $pdu->getPacketSize() - 24);
        }

        $pdu = new RawConnectionOrientedPdu($bytes, $packetSize, $flags, $version, $type);

        Text::dumpHex($pdu->getBytes(), 'BYTES');

        return [ $pdu ];
    }
}
