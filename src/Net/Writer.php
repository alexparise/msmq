<?php

namespace Aztech\Net;

interface Writer
{

    public function write($buffer);

    /**
     * Shortcut for write(chr($char)).
     *
     * @param int $char
     */
    public function writeChr($char);

    public function writeInt16($value, $byteOrder = ByteOrder::LITTLE_ENDIAN);

    public function writeInt32($value, $byteOrder = ByteOrder::LITTLE_ENDIAN);

    public function writeUInt16($value, $byteOrder = ByteOrder::LITTLE_ENDIAN);

    public function writeUInt32($value, $byteOrder = ByteOrder::LITTLE_ENDIAN);
}
