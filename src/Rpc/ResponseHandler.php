<?php

namespace Aztech\Rpc;

interface ResponseHandler
{
    public function handleResponse(ProtocolDataUnit $request, ProtocolDataUnit $response);
}