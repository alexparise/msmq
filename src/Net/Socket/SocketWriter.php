<?php

namespace Aztech\Net\Socket;

use Aztech\Net\AbstractWriter;
use Aztech\Net\Socket as SocketInterface;

class SocketWriter extends AbstractWriter
{

    private $socket;

    public function __construct(SocketInterface $socket, $byteOrder)
    {
        $this->socket = $socket;

        parent::__construct($byteOrder);
    }

    public function write($buffer)
    {
        return $this->socket->writeRaw($buffer);
    }

}
