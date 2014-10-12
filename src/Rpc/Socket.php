<?php

namespace Aztech\Rpc;

use Aztech\Net\Socket\Socket as BaseSocket;

class Socket extends BaseSocket
{

    public function getReader()
    {
        return new SocketReader($this);
    }

}
