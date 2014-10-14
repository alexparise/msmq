<?php

namespace Aztech\Rpc\Pdu\ConnectionOriented;

use Rhumsaa\Uuid\Uuid;

class BindContext
{

    private $associationGroup = 0x00;

    private $callId;

    private $items;

    private $noAuth = false;

    public function __construct($callId, $noAuth = false, $associationGroupId = 0x00)
    {
        $this->items = [];
        $this->callId = $callId;
        $this->noAuth = $noAuth;
        $this->associationGroup = $associationGroupId;
    }

    public function getCallId()
    {
        return $this->callId;
    }

    public function getAssociationGroup()
    {
        return $this->associationGroup;
    }

    public function setAssociationGroup($group)
    {
        $this->associationGroup = $group;
    }

    public function isAuthDisabled()
    {
        return $this->noAuth;
    }

    public function disableAuth()
    {
        $this->noAuth = true;
    }

    /**
     *
     * @return BindContextItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    public function getItemCount()
    {
        return count($this->items);
    }

    public function addItem(Uuid $syntax, $version, array $transferSyntax)
    {
        $item = new BindContextItem($this->getItemCount(), $syntax, $version);

        foreach ($transferSyntax as $syntax) {
            $item->addSyntax($syntax);
        }

        $this->items[] = $item;
    }
}
