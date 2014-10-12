<?php

namespace Aztech\Ntlm\Message;

use Aztech\Net\PacketWriter;
use Aztech\Util\Text;
use Aztech\Net\ByteOrder;

class SecurityBufferedContentBuilder
{

    const BUFFER_SIZE = 8;

    private $parts = [];

    public function add($part, $unicode = false, $forcedLength = false)
    {
        if ($unicode) {
            $part = Text::toUnicode($part);
        }

        $this->parts[] = $part;
    }

    public function getHeaders($offset)
    {
        $buffers = new PacketWriter();
        $offset  += (count($this->parts) * self::BUFFER_SIZE);

        foreach ($this->parts as $part) {
            $buffers->writeUInt16(strlen($part), ByteOrder::LITTLE_ENDIAN);
            $buffers->writeUInt16(strlen($part), ByteOrder::LITTLE_ENDIAN);
            $buffers->writeUInt32($offset, ByteOrder::LITTLE_ENDIAN);

            $offset += strlen($part);
        }

        return $buffers->getBuffer();
    }

    public function getContent($offset)
    {
        $buffers = new PacketWriter();
        $content = new PacketWriter();
        $offset  += (count($this->parts) * self::BUFFER_SIZE);

        foreach ($this->parts as $part) {
            $content->write($part);
        }

        return $content->getBuffer();
    }
}
