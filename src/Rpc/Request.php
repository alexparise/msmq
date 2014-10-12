<?php

namespace Aztech\Rpc;

interface Request
{
    
    public function getContent();
    
    public function getType();
    
    public function getFlags();
}