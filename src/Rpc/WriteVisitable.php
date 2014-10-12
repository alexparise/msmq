<?php

namespace Aztech\Rpc;

interface WriteVisitable
{
    public function accept(WriteVisitor $writer);
}
