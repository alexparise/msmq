<?php

namespace Aztech\Ntlm\Message;

use Aztech\Net\PacketWriter;
use Aztech\Util\Text;
use Aztech\Net\ByteOrder;
use Aztech\Net\Buffer\BufferWriter;

class SecurityBufferedContentBuilder
{

    const BUFFER_SIZE = 8;

    private $parts = [];

    private $reversed = false;

    public function __construct($reversed = false)
    {
        $this->reversed = (bool) $reversed;
    }

    public function add($part, $unicode = false, $forcedLength = false)
    {
        if ($unicode) {
            $part = Text::toUnicode($part);
        }

        $this->parts[] = $part;
    }

    public function getHeaders($offset)
    {
        $buffers = new BufferWriter();
        $offset  += (count($this->parts) * self::BUFFER_SIZE);

        if ($this->reversed) {
            foreach ($this->parts as $part) {
                $offset += strlen($part);
            }
        }

        foreach ($this->parts as $part) {
            if ($this->reversed) {
                $offset -= strlen($part);
            }

            $buffers->writeUInt16(strlen($part), ByteOrder::LITTLE_ENDIAN);
            $buffers->writeUInt16(strlen($part), ByteOrder::LITTLE_ENDIAN);
            $buffers->writeUInt32($offset, ByteOrder::LITTLE_ENDIAN);

            if (! $this->reversed) {
                $offset += strlen($part);
            }
        }

        return $buffers->getBuffer();
    }

    public function getContent($offset)
    {
        $buffers = new BufferWriter();
        $content = new BufferWriter();
        $parts = $this->parts;

        if ($this->reversed) {
            //$parts = array_reverse($parts);
        }

        foreach ($parts as $part) {
            $content->write($part);
        }

        return $content->getBuffer();
    }
}
