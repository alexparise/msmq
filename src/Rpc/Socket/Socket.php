<?php

namespace Aztech\Rpc\Socket;

use Aztech\Net\ByteOrder;
use Aztech\Net\Socket as SocketInterface;
use Aztech\Rpc\Parser\ConnectionOrientedPduParser;
use Aztech\Rpc\Parser\RawConnectionOrientedPduParser;

class Socket implements SocketInterface
{
    private $socket;

    public function __construct(SocketInterface $socket)
    {
        $this->socket = $socket;
    }

    public function getReader()
    {
        $headerParser = new RawConnectionOrientedPduParser();
        $parser = new ConnectionOrientedPduParser();

        return new SocketReader($this->socket->getReader(), $headerParser, $parser);
    }

    public function getWriter()
    {
        return new SocketWriter($this->socket->getWriter(), $this, ByteOrder::LITTLE_ENDIAN);
    }

    public function readRaw($bytes)
    {
        return $this->socket->readRaw($bytes);
    }

    public function writeRaw($buffer)
    {
        return $this->socket->writeRaw($buffer);
    }
}
