<?php

namespace Aztech\Net\Buffer;

use Aztech\Net\AbstractReader;
use Aztech\Util\Text;

class BufferReader extends AbstractReader
{

    private $data;

    private $offset = 0;

    public function __construct($dataBuffer)
    {
        $this->data = $dataBuffer;
    }

    /**
     * Reads a number of bytes.
     * @param int $length Number of bytes to read, or 0 to read to end. If length is greater than the available characters, it is truncated to that count.
     * @return false|string False if no bytes are available, or the read bytes.
     */
    public function read($length = 0)
    {
        if ($this->offset >= strlen($this->data)) {
            return false;
        }

        if ($length <= 0 || $this->offset + $length >= strlen($this->data)) {
            $length = strlen(substr($this->data, $this->offset));
        }

        $offset = $this->offset;
        $this->offset += $length;

        return substr($this->data, $offset, $length);
    }

    public function readFrom($offset, $length = 0)
    {
        if ($offset >= strlen($this->data)) {
            return false;
        }

        if ($length <= 0 || $offset + $length >= strlen($this->data)) {
            return substr($this->data, $offset);
        }

        return substr($this->data, $offset, $length);
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getReadByteCount()
    {
        return $this->offset;
    }

    public function getBuffer()
    {
        return $this->data;
    }
}
