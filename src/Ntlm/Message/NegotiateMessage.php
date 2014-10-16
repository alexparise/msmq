<?php

namespace Aztech\Ntlm\Message;

use Aztech\Ntlm\Message;
use Aztech\Ntlm\NTLMSSP;
use Aztech\Net\Buffer\BufferWriter;
use Aztech\Ntlm\Credentials;

class NegotiateMessage implements Message
{

    private $credentials;

    private $flags;

    public function __construct(Credentials $credentials, $flags)
    {
        $this->credentials = $credentials;
        $this->flags = $flags;
    }

    public function getType()
    {
        return NTLMSSP::MSG_NEGOTIATE;
    }

    public function getContent($offset)
    {
        $builder = new SecurityBufferedContentBuilder();

        $builder->add(trim($this->credentials->getDomain()));
        $builder->add(trim($this->credentials->getWorkstation()));

        $writer = new BufferWriter();

        $writer->writeUInt32($this->flags);
        $writer->write($builder->getHeaders($offset + 4));
        $writer->write($builder->getContent($offset + 4));

        return $writer->getBuffer();
    }
}
