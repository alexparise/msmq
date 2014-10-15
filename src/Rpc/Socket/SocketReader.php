<?php

namespace Aztech\Rpc\Socket;

use Aztech\Net\Socket\SocketReader as BaseSocketReader;
use Aztech\Rpc\PduHeaderParser;
use Aztech\Rpc\PduParser;
use Aztech\Rpc\RawPduParser;
use Aztech\Rpc\Pdu\FragmentedRawPdu;
use Aztech\Rpc\ProtocolDataUnit;

class SocketReader
{

    private $parser;

    private $rawParser;

    private $reader;

    public function __construct(BaseSocketReader $reader, RawPduParser $headerParser, PduParser $parser)
    {
        $this->parser = $parser;
        $this->rawParser = $headerParser;
        $this->reader = $reader;
    }

    /**
     * @return ProtocolDataUnit
     */
    public function readNextPdu()
    {
        $rawPdus = [];

        do {
            $this->reader->resetReadCount();

            $rawPdu    = $this->rawParser->parse($this->reader);
            $rawPdus[] = $rawPdu;
        }
        while (! $rawPdu->isLastFragment());

        return $this->parser->parse(new FragmentedRawPdu($rawPdus));
    }
}
