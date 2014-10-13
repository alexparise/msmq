<?php

namespace Aztech\Rpc\Parser\ConnectionOriented;

use Aztech\Rpc\DataRepresentationFormat;
use Aztech\Net\Buffer\BufferReader;

abstract class AbstractParser
{

    const ALIGN_VER_MAJOR = 0;

    const ALIGN_VER_MINOR = 1;

    const ALIGN_TYPE = 2;

    const ALIGN_FLAGS = 3;

    const ALIGN_FORMAT = 4;

    const ALIGN_FRAG_LEN = 8;

    const ALIGN_AUTH_LEN = 10;

    const ALIGN_CALL_ID = 12;

    protected function parseDataRepresentationFormat(BufferReader $reader)
    {
        $reader->seek(self::ALIGN_FORMAT);

        $format    = $reader->readUInt32();

        $intType   = ($format << 28) >> 28;
        $charType  = ($format << 24) >> 28;
        $floatType = ($format << 20) >> 28;

        return new DataRepresentationFormat($charType, $intType, $floatType);
    }
}
