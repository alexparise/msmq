<?php

namespace Aztech\Ntlm\Message;

use Aztech\Util\Text;
use Aztech\Net\PacketReader;
use Aztech\Net\Buffer\BufferReader;

class NtlmPacketReader extends BufferReader
{

    public function readSignature()
    {
        return $this->read(8);
    }

    /**
     * Reads a string's properties from its security buffer offset and returns the string value.
     * @return string
     */
    public function readString()
    {
        $len = $this->readInt16();
        $maxLen = $this->readInt16();
        $strOffset = $this->readInt32();

        return $this->readFrom($strOffset, $maxLen * 2);
    }

    /**
     * Reads a Unicode string's properties from its security buffer offset and returns the string value.
     * @param int $offset Offset of the security buffer pointing to the desired string.
     * @return string
     */
    public function readUnicodeString($offset)
    {
        $string = $this->readString($offset);

        return Util::deUnicodify($string);
    }
}
