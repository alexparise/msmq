<?php

namespace Aztech\Rpc;

interface RawProtocolDataUnit
{

    public function getBytes();

    public function getPacketSize();

    public function getFlags();

    public function getType();

    public function getVersion();

    public function isLastFragment();

}
