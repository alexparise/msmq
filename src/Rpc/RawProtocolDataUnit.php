<?php

namespace Aztech\Rpc;

interface RawProtocolDataUnit
{

    public function getBytes();

    public function getPacketSize();

    public function getType();

    public function getVersion();

    public function isLastFragment();

}
