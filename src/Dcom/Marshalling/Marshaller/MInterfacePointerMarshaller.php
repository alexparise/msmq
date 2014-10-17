<?php

namespace Aztech\Dcom\Marshalling\Marshaller;

use Aztech\Dcom\Marshalling\Marshaller;
use Aztech\Net\Reader;
use Aztech\Net\Writer;
use Aztech\Net\Buffer\BufferWriter;
use Aztech\Dcom\Pointer;

class MInterfacePointerMarshaller extends Pointer implements Marshaller
{
    private $marshaller;
    
    public function __construct()
    {
        $this->marshaller = new ObjRefMarshaller();
    }
    
    public function marshall(Writer $writer, $value)
    {
        $buffer = new BufferWriter();
        
        $this->marshaller->marshall($buffer, $value->getObjRef());
        
        $writer->writeUInt32(++self::$counter);
        $writer->writeUInt32($buffer->getBufferSize());
        $writer->write($buffer->getBufferSize());
    }
    
    public function unmarshallNext(Reader $reader)
    {
        
    }
}