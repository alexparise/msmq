<?php

namespace Aztech\Net;

class SocketWriter extends AbstractWriter
{

    private $socket;

    public function __construct(Socket $socket)
    {
        $this->socket = $socket;
    }

    public function write($buffer)
    {
        return $this->socket->writeRaw($buffer);
    }

}
