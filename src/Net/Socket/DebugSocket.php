<?php

namespace Aztech\Net\Socket;

use Aztech\Net\Socket as SocketInterface;
use Aztech\Net\SocketReader;
use Aztech\Net\SocketWriter;
use Aztech\Util\Text;

class DebugSocket implements SocketInterface
{

    private $socket;

    public function __construct(SocketInterface $socket)
    {
        $this->socket = $socket;
    }

    public function getReader()
    {
        return new SocketReader($this);
    }

    public function getWriter()
    {
        return new SocketWriter($this);
    }

    public function readRaw($bytes)
    {
        $read = $this->socket->readRaw($bytes);

        echo PHP_EOL . time() . ' : Socket read ' . strlen($read) . ' bytes of ' . $bytes . ' requested : ' . PHP_EOL;
        Text::dumpHex($read);

        return $read;
    }

    public function writeRaw($buffer)
    {
        $this->socket->writeRaw($buffer);

        echo PHP_EOL . time() . ' : Socket wrote ' . strlen($buffer) . ' bytes : ' . PHP_EOL;
        Text::dumpHex($buffer);
    }
}
