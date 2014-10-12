<?php

namespace Aztech\Rpc\Pdu\ConnectionOriented;

use Rhumsaa\Uuid\Uuid;

class BindContextItem
{

    private $contextId;

    private $abstractSyntax;

    private $version;

    private $transferSyntaxes;

    public function __construct($contextId, Uuid $abstractSyntax, $version)
    {
        $this->contextId = $contextId;
        $this->abstractSyntax = $abstractSyntax;
        $this->version = $version;
        $this->transferSyntaxes = [];
    }

    public function addSyntax(Uuid $interface, $version)
    {
        $this->transferSyntaxes[] = [ $interface, $version ];
    }

    public function getContextId()
    {
        return $this->contextId;
    }

    public function getAbstractSyntax()
    {
        return $this->abstractSyntax;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getTransferSyntaxCount()
    {
        return count($this->transferSyntaxes);
    }

    public function getTransferSyntaxes()
    {
        return $this->transferSyntaxes;
    }
}
