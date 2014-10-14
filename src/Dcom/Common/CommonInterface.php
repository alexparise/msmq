<?php

namespace Aztech\Dcom\Common;

use Aztech\Dcom\DcomInterface;
use Aztech\Rpc\Client;
use Rhumsaa\Uuid\Uuid;
use Aztech\Rpc\TransferSyntax;

abstract class CommonInterface extends DcomInterface
{
    protected $client;

    abstract protected function getIid();

    protected function getSyntaxes()
    {
        $transferSyntaxes = [];

        $transferSyntaxes[] = TransferSyntax::getNdr();
        $transferSyntaxes[] = TransferSyntax::getNegotiate();

        return $transferSyntaxes;
    }

    public function __construct(Client $client)
    {
        $this->client = $client;

        $iid = $this->getIid();

        $transferSyntaxes = $this->getSyntaxes();

        parent::__construct($iid, 0x00, $transferSyntaxes);
    }

}
