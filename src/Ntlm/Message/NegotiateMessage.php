<?php

namespace Aztech\Ntlm\Message;

use Aztech\Ntlm\Message;
use Aztech\Ntlm\NTLMSSP;
use Aztech\Net\Buffer\BufferWriter;

class NegotiateMessage implements Message
{

    private $domain;

    private $machine;

    private $flags;

    public function __construct($domain, $machine, $flags)
    {
        if (trim($domain) == '' || trim($machine) == '') {
            throw new \InvalidArgumentException('Domain and machine name must be non empty strings.');
        }

        $this->domain = $domain;
        $this->machine = $machine;
        $this->flags = $flags;
    }

    public function getType()
    {
        return NTLMSSP::MSG_NEGOTIATE;
    }

    public function getContent($offset)
    {
        $builder = new SecurityBufferedContentBuilder();

        $builder->add($this->domain);
        $builder->add($this->machine);

        $writer = new BufferWriter();

        $writer->writeUInt32($this->flags);
        $writer->write($builder->getHeaders($offset + 4));
        $writer->write($builder->getContent($offset + 4));

        return $writer->getBuffer();
    }
}
