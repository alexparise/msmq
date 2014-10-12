<?php

namespace Aztech\Rpc\Pdu;

use Aztech\Net\ByteOrder;
use Aztech\Net\PacketWriter;
use Aztech\Util\Text;
use Aztech\Net\Reader;
use Aztech\Rpc\Rpc;

class ConnectionOrientedHeader
{

    public static function parse(Reader $reader)
    {
        $major = ord($reader->read(1));
        $minor = ord($reader->read(1));
        $type = ord($reader->read(1));
        $flags = ord($reader->read(1));
        $unpackedDR = unpack('H*', $reader->read(4));
        $dataRepresentation = reset($unpackedDR);
        $packetSize = $reader->readUInt16(ByteOrder::LITTLE_ENDIAN);
        $authSize = $reader->readUInt16(ByteOrder::LITTLE_ENDIAN);

        $header = new static($type, $flags, $packetSize, $authSize, $reader->getReadByteCount());
        $header->setDataRepresentation($dataRepresentation);
        $header->setVersion($major, $minor);

        return $header;
    }

    private $dataRepresentation = '10000000';

    private $major = Rpc::VERSION_MAJOR;

    private $minor = Rpc::VERSION_MINOR;

    private $authSize;

    private $flags;

    private $packetSize;

    private $type;

    private $isFirstFrag;

    private $isLastFrag;

    public function __construct($type, $flags, $packetSize, $authSize)
    {
        $this->type = $type;
        $this->flags = $flags;
        $this->packetSize = $packetSize;
        $this->authSize = $authSize;

        $this->isFirstFrag = (bool)($flags & Rpc::PFC_FIRST_FRAG);
        $this->isLastFrag = (bool)($flags & Rpc::PFC_LAST_FRAG);
    }

    public function setDataRepresentation($representation)
    {
        $this->dataRepresentation = $representation;
    }

    public function setVersion($major, $minor)
    {
        $this->major = $major;
        $this->minor = $minor;
    }

    public function getAuthSize()
    {
        return $this->authSize;
    }

    public function getFlags()
    {
        return $this->flags;
    }

    public function getPacketSize()
    {
        return $this->packetSize;
    }

    public function getType()
    {
        return $this->type;
    }

    public function isLastFragment()
    {
        return $this->isLastFrag;
    }

    public function isFirstFragment()
    {
        return $this->isFirstFrag;
    }

    public function dump()
    {
        echo PHP_EOL . 'Connection-Oriented Header :' . PHP_EOL;

        echo '* Type = ' . $this->type . PHP_EOL;
        echo '* Flags = ' . $this->getPrettyFlags() . PHP_EOL;
        echo '* IsFirstFragment = ' . ($this->isFirstFrag ? 'true' : 'false') . PHP_EOL;;
        echo '* IsLastFragment = ' . ($this->isLastFrag ? 'true' : 'false') . PHP_EOL;
        echo '* PacketSize = ' . $this->packetSize . PHP_EOL;
        echo '* AuthSize = ' . $this->authSize . PHP_EOL;
        echo '* Data representation = ' . $this->dataRepresentation . PHP_EOL;
        echo '* Hex dump :' . PHP_EOL;

        Text::dumpHex($this->getBytes());

        echo PHP_EOL;
    }

    public function getPrettyFlags()
    {
        return $this->flags;
    }

    public function getBytes()
    {
        $writer = new PacketWriter();

        $writer->writeChr($this->major);
        $writer->writeChr($this->minor);
        $writer->writeChr($this->type);
        $writer->writeChr($this->flags);
        $writer->writeHex($this->dataRepresentation, 8);
        $writer->writeUInt16(Rpc::CO_HDR_SZ + $this->packetSize + $this->authSize, ByteOrder::LITTLE_ENDIAN);
        $writer->writeUInt16($this->authSize, ByteOrder::LITTLE_ENDIAN);
        $writer->writeUInt32(0x00, ByteOrder::LITTLE_ENDIAN);

        return $writer->getBuffer();
    }
}
