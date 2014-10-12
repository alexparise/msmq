<?php

namespace Aztech\Rpc;

use Aztech\Net\PacketReader;

class Response
{
    private $header;

    private $body;

    public function __construct(ConnectionOrientedHeader $header, $body)
    {
        $this->header = $header;
        $this->body = $body;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getBytes()
    {
        return new PacketReader($this->header->getBytes() . $this->body);
    }
}
