<?php

namespace Aztech\Net;

interface Socket
{

    /**
     *
     * @return SocketReader
     */
    public function getReader();

    /**
     *
     * @return SocketWriter
     */
    public function getWriter();

    /**
     *
     * @param int $bytes
     * @return string
     */
    public function readRaw($bytes);

    /**
     *
     * @param string $buffer
     * @return void
     */
    public function writeRaw($buffer);

}
