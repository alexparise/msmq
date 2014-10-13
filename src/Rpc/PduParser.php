<?php

namespace Aztech\Rpc;

interface PduParser
{

    public function parse(RawProtocolDataUnit $rawPdu);
}
