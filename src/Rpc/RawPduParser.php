<?php

namespace Aztech\Rpc;

use Aztech\Net\Reader;

interface RawPduParser
{
    /**
     *
     * @param Reader $reader
     * @return RawProtocolDataUnit
     */
    public function parse(Reader $reader);
}
