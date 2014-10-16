<?php

namespace Aztech\Dcom\Marshalling;

use Aztech\Net\Buffer\BufferWriter;
use Aztech\Util\Text;

class MarshalledBuffer
{
    private $writer;

    private $map;

    public function __construct()
    {
        $this->writer = new BufferWriter();
    }

    public function add(Marshaller $marshaller, $value)
    {
        $marshaller->marshall($this->writer, $value);
    }

    public function align()
    {
        while ($this->writer->getBufferSize() % 4 !== 0) {
            $this->writer->writeChr(0);
        }
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
