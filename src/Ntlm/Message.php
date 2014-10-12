<?php

namespace Aztech\Ntlm;

interface Message
{

    /**
     * Returns the message content as a string
     *
     * @return string
     */
    function getContent($offset);

    /**
     * Returns one of the NTLMSSP::MSG_* constants indicating the message type.
     *
     * @return int
     */
    function getType();
}
