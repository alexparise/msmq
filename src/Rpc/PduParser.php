<?php

namespace Aztech\Rpc;

interface PduParser
{
    
    public function parse(PduFieldCollection $header, Reader $reader);
}