<?php

namespace Aztech\Dcom\Common;

use Aztech\Dcom\DcomInterface;
use Aztech\Dcom\OrpcThis;
use Aztech\Rpc\Client;
use Aztech\Rpc\TransferSyntax;
use Rhumsaa\Uuid\Uuid;

abstract class CommonInterface extends DcomInterface
{
    protected $client;

    protected $orpcThis;

    abstract protected function getIid();

    protected function getOrpcThis()
    {
        return $this->orpcThis;
    }

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
        $this->orpcThis = new OrpcThis(Uuid::uuid4());

        $iid = $this->getIid();

        $transferSyntaxes = $this->getSyntaxes();

        parent::__construct($iid, 0x00, $transferSyntaxes);
    }

}
