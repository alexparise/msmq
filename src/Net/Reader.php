<?php

namespace Aztech\Net;

interface Reader
{

    public function read($length = 0);

    public function readInt16($byteOrder = ByteOrder::BO_MACHINE);

    public function readInt32($byteOrder = ByteOrder::BO_MACHINE);

    public function readUInt16($byteOrder = ByteOrder::BO_MACHINE);

    public function readUInt32($byteOrder = ByteOrder::BO_MACHINE);

    public function getReadByteCount();
}
