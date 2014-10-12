<?php

namespace Aztech\Net\Socket;

use Aztech\Net\Socket as SocketInterface;
use Aztech\Net\SocketReader;
use Aztech\Net\SocketWriter;

class Socket implements SocketInterface
{

    /**
     *
     * @var string
     */
    private $host;

    /**
     *
     * @var string|int
     */
    private $port;

    /**
     *
     * @var resource
     */
    private $socket;

    /**
     *
     * @param string $host
     * @param string|int $port
     * @throws \RuntimeException
     */
    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if (! socket_connect($this->socket, $this->host, $this->port)) {
            $message = sprintf(
                'Could not connect to %s:%s [%s]',
                $this->host,
                $this->port,
                $this->getLastError()
            );

            throw new \RuntimeException($message);
        }
    }

    /**
     *
     * @return \Aztech\Net\SocketReader
     */
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
        if (($received = socket_read($this->socket, $bytes, PHP_BINARY_READ)) && $received === false) {
            throw new \RuntimeException(sprintf(
                'Unable to read from socket : %s',
                $this->getLastError()
            ));
        }

        return $received;
    }

    public function writeRaw($buffer)
    {
        if (false === ($written = socket_write($this->socket, $buffer))) {
            throw new \RuntimeException(sprintf(
                'Unable to write to socket : %s',
                $this->getLastError()
            ));
        }

        return $written;
    }

    /**
     *
     * @return string
     */
    protected function getLastError()
    {
        return socket_strerror(socket_last_error());
    }

    /**
     *
     * @return resource A valid and connected Socket resource.
     */
    protected function getSocket()
    {
        return $this->socket;
    }
}
