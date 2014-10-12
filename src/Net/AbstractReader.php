<?php

namespace Aztech\Net;

abstract class AbstractReader implements Reader
{

    const INT_16_SZ = 2;

    const INT_32_SZ = 4;

    public function readInt16($byteOrder = ByteOrder::LITTLE_ENDIAN)
    {
        $format = ByteOrder::getPackFormat($byteOrder, ByteOrder::FMT_INT_16);
        $unpacked = unpack($format, $this->read(static::INT_16_SZ));

        return reset($unpacked);
    }

    public function readInt32($byteOrder = ByteOrder::LITTLE_ENDIAN)
    {
        $format = ByteOrder::getPackFormat($byteOrder, ByteOrder::FMT_INT_32);
        $unpacked = unpack($format, $this->read(static::INT_32_SZ));

        return reset($unpacked);
    }

    public function readUInt16($byteOrder = ByteOrder::LITTLE_ENDIAN)
    {
        $format = ByteOrder::getPackFormat($byteOrder, ByteOrder::FMT_UINT_16);
        $unpacked = unpack($format, $this->read(static::INT_16_SZ));

        return reset($unpacked);
    }

    public function readUInt32($byteOrder = ByteOrder::LITTLE_ENDIAN)
    {
        $format = ByteOrder::getPackFormat($byteOrder, ByteOrder::FMT_UINT_32);
        $unpacked = unpack($format, $this->read(static::INT_32_SZ));

        return reset($unpacked);
    }

    public function skip($count)
    {
        $this->read($count);
    }

}
