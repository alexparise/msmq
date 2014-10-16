<?php

namespace Aztech\Rpc;

interface ResponseHandler
{

    public function handleResponseForBind(ProtocolDataUnit $request, ProtocolDataUnit $response);

    public function handleResponse(ProtocolDataUnit $request, ProtocolDataUnit $response);
}
