<?php

namespace Aztech\Dcom\Common;

use Aztech\Dcom\DcomInterface;
use Aztech\Rpc\Client;
use Rhumsaa\Uuid\Uuid;
use Aztech\Dcom\TransferSyntax;

abstract class CommonInterface extends DcomInterface
{
    protected $client;

    abstract protected function getIid();
    
    public function __construct(Client $client)
    {
        $this->client = $client;
        
        $iid = $this->getIid();
        $transferSyntax = Uuid::fromBytes(hex2bin(TransferSyntax::NDR));
        $transferSyntaxVersion = TransferSyntax::NDR_VERSION;
        
        parent::__construct($iid, 0x00, $transferSyntax, $transferSyntaxVersion);
    }
}