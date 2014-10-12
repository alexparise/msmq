<?php

namespace Aztech\Net\Buffer;

use Aztech\Util\Text;

class BufferWriter extends AbstractWriter
{
    private $buffer = '';

    public function write($buffer)
    {
        $this->buffer .= $buffer;
    }

    public function getBuffer()
    {
        return $this->buffer;
    }

    public function getBufferSize()
    {
        return strlen($this->buffer);
    }
}
