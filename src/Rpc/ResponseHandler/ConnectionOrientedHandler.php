<?php

namespace Aztech\Rpc\ResponseHandler;

use Aztech\Rpc\Client;
use Aztech\Rpc\PduType;
use Aztech\Rpc\ProtocolDataUnit;
use Aztech\Rpc\ResponseHandler;
use Aztech\Rpc\Auth\AuthenticationStrategy;
use Aztech\Rpc\Pdu\ConnectionOriented\BindPdu;
use Aztech\Rpc\Pdu\ConnectionOriented\BindNackPdu;
use Aztech\Rpc\Pdu\ConnectionOriented\BindAckPdu;
use Aztech\Rpc\Pdu\ConnectionOriented\BindResponsePdu;
use Aztech\Rpc\Pdu\ConnectionOriented\FaultPdu;
use Aztech\Rpc\NcaStatus;

class ConnectionOrientedHandler implements ResponseHandler
{
    private $client;

    private $authStrategy;

    public function __construct(Client $client, AuthenticationStrategy $authStrategy)
    {
        $this->client = $client;
        $this->authStrategy = $authStrategy;
    }

    public function handleResponseForBind(ProtocolDataUnit $request, ProtocolDataUnit $response)
    {
        if ($request instanceof BindPdu) {
            if ($response instanceof BindNackPdu) {
                $errorCode = $response->getReason();
                $format = 'Bind rejected : 0x%08x.';

                $this->handleError(
                    $errorCode,
                    null,
                    $format,
                    $errorCode
                );
            }
            elseif ($response instanceof BindAckPdu) {
                return $this->respondToBindAck($response);
            }
        }

        throw new \RuntimeException('Invalid response type.');
    }

    public function handleResponse(ProtocolDataUnit $request, ProtocolDataUnit $response)
    {
        if ($response instanceof FaultPdu) {
            $errorCode = $response->getErrorCode();
            $format = 'RPC error : %s (0x%08x).';

            $this->handleError(
                $errorCode,
                null,
                $format,
                NcaStatus::getErrorName($errorCode) ?: 'unknown',
                $errorCode
            );
        }

        return $response;
    }

    protected function handleError($errorCode, Exception $previous = null, $format)
    {
        $message = call_user_func_array('sprintf', array_slice(func_get_args(), 2));

        throw new \RuntimeException($message, $errorCode, $previous);
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
