<?php

namespace Aztech\Dcom\Marshalling;

use Aztech\Net\Writer;
use Aztech\Net\Reader;

class UnmarshallingBuffer
{
    
    private $marshallMap;
    
    public function __construct()
    {
        $this->marshallMap = new MarshallMap();        
    }
    
    public function getMap()
    {
        return $this->marshallMap;
    }
    
    public function parseValues(Reader $reader)
    {
        
    }
}