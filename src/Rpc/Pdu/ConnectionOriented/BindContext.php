<?php

namespace Aztech\Rpc\Pdu\ConnectionOriented;

use Rhumsaa\Uuid\Uuid;

class BindContext
{

    private $items;

    public function __construct()
    {
        $this->items = [];
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
            $item->addSyntax($syntax[0], $syntax[1]);
        }

        $this->items[] = $item;
    }
}
