<?php

namespace Aztech\Rpc;

use Aztech\Net\Socket\DebugSocket;
use Aztech\Net\Socket\Socket as BaseSocket;
use Aztech\Rpc\Auth\AuthenticationStrategyProvider;
use Aztech\Rpc\Socket\Socket;

class Factory
{

    public static function get($debug = false)
    {
        return new self($debug);
    }

    private $debug = false;

    private function __construct($debug)
    {
        $this->debug = (bool) $debug;
    }

    public function getClient(AuthenticationStrategyProvider $authProvider, $host, $port)
    {
        if (filter_var($host, FILTER_VALIDATE_IP) === false) {
            $host = gethostbyname($host);
        }

        $socket = new BaseSocket($host, $port, true);

        if ($this->debug) {
            $socket = new DebugSocket($socket);
        }

        return new Client(new Socket($socket), $authProvider);
    }
}
