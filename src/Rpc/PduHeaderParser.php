<?php

namespace Aztech\Rpc;

interface PduHeaderParser
{
    
    public function parse(Reader $reader);
}