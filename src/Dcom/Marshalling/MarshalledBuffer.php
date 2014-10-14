<?php

namespace Aztech\Dcom\Marshalling;

use Aztech\Net\Buffer\BufferWriter;
class MarshalledBuffer
{
    private $writer;
    
    public function __construct()
    {
        $this->writer = new BufferWriter();
    }
    
    public function getWriter()
    {
        return $this->writer;
    }
    
    public function getBytes()
    {
        return $this->writer->getBuffer();
    }
}