<?php

namespace Aztech\Rpc\Parser;

use Aztech\Net\DataTypes;
use Aztech\Rpc\RawPduParser;
use Aztech\Net\Reader;
use Aztech\Net\Buffer\BufferReader;
use Aztech\Rpc\Pdu\RawConnectionOrientedPdu;

class RawConnectionOrientedPduParser implements RawPduParser
{
    public function parse(Reader $reader)
    {
        $headerSize = DataTypes::INT8_SZ * 4 + DataTypes::INT16_SZ * 2 + DataTypes::INT32_SZ;
        $bytes      = $reader->read($headerSize);

        $buffer     = new BufferReader($bytes);

        $major      = $buffer->readUInt8();
        $minor      = $buffer->readUInt8();
        $type       = $buffer->readUInt8();
        $flags      = $buffer->readUInt8();
        $drf        = $buffer->readUInt32();
        $packetSize = $buffer->readUInt16();
        $authSize   = $buffer->readUInt16();

        while ($reader->getReadByteCount() != $packetSize) {
            $bytes .= $reader->read($packetSize - $reader->getReadByteCount());
        }

        $rawPdu     = new RawConnectionOrientedPdu($bytes, $packetSize, $flags, $major . '.' . $minor, $type);

        return $rawPdu;
    }
}
