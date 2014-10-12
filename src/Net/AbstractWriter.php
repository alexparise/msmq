<?php

namespace Aztech\Net;

abstract class AbstractWriter implements Writer
{

    private $machineByteOrder;

    public function __construct()
    {
        $machineValue = pack('L', 4294901760);
        $littleEndianValue = pack('V', 4294901760);

        $this->machineByteOrder = ($machineValue == $littleEndianValue) ? ByteOrder::LITTLE_ENDIAN : ByteOrder::BIG_ENDIAN;
    }

    public function writeChr($char)
    {
        return $this->write(chr($char));
    }

    public function writeInt16($value, $byteOrder = ByteOrder::LITTLE_ENDIAN)
    {
        $format = ByteOrder::getPackFormat($byteOrder, ByteOrder::FMT_INT_16);

        return $this->write(pack($format, $value));
    }

    public function writeInt32($value, $byteOrder = ByteOrder::LITTLE_ENDIAN)
    {
        $format = ByteOrder::getPackFormat($byteOrder, ByteOrder::FMT_INT_32);

        return $this->write(pack($format, $value));
    }

    public function writeUInt16($value, $byteOrder = ByteOrder::LITTLE_ENDIAN)
    {
        $format = ByteOrder::getPackFormat($byteOrder, ByteOrder::FMT_UINT_16);

        return $this->write(pack($format, $value));
    }

    public function writeUInt32($value, $byteOrder = ByteOrder::LITTLE_ENDIAN)
    {
        $format = ByteOrder::getPackFormat($byteOrder, ByteOrder::FMT_UINT_32);

        return $this->write(pack($format, $value));
    }

    public function writeHex($value, $size = 0, $byteOrder = ByteOrder::LITTLE_ENDIAN)
    {
        if ($byteOrder == ByteOrder::MACHINE) {
            $byteOrder = $this->machineByteOrder;
        }

        $format = ($byteOrder == ByteOrder::LITTLE_ENDIAN) ? 'H' : 'h';

        if ($size != 0) {
            $format .= $size;
        }

        return $this->write(pack($format , $value));
    }
}
