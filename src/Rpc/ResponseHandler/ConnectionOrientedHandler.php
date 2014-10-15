<?php

namespace Aztech\Rpc\ResponseHandler;

use Aztech\Rpc\ResponseHandler;
use Aztech\Rpc\ProtocolDataUnit;
use Aztech\Rpc\Pdu\ConnectionOriented\BindPdu;
use Aztech\Rpc\Pdu\ConnectionOriented\BindNackPdu;
use Aztech\Rpc\Pdu\ConnectionOriented\BindAckPdu;
use Aztech\Rpc\Client;
use Aztech\Rpc\Pdu\ConnectionOriented\BindResponsePdu;
use Aztech\Rpc\AuthenticationStrategy;
use Aztech\Rpc\PduType;

class ConnectionOrientedHandler implements ResponseHandler
{
    private $client;

    private $authStrategy;

    public function __construct(Client $client, AuthenticationStrategy $authStrategy)
    {
        $this->client = $client;
        $this->authStrategy = $authStrategy;
    }

    public function handleResponse(ProtocolDataUnit $request, ProtocolDataUnit $response)
    {
        if ($request instanceof BindPdu) {
            if ($response instanceof BindNackPdu) {
                throw new \RuntimeException('Bind rejected (Reason : 0x' . dechex($response->getReason()) . ')', $response->getReason());
            }
            elseif ($response instanceof BindAckPdu) {
                return $this->respondToBindAck($response);
            }
        }

        throw new \RuntimeException('Invalid response type.');
    }

    protected function respondToBindAck(BindAckPdu $ack)
    {
        if ($ack->getRawAuthData()) {
            $verifier = $this->authStrategy->getVerifier(
                PduType::BIND_RESP,
                $this->client->getAuthenticationContext(),
                $ack
            );

            $response = new BindResponsePdu($verifier);
            $response->setCallId($ack->getCallId());

            $this->client->request($response);
        }

        return $ack;
    }
}
