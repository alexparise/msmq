<?php

namespace Aztech\Net;

use Aztech\Util\Text;

class PacketWriter extends AbstractWriter
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
