<?php

namespace Aztech\Rpc;

use Aztech\Rpc\Auth\AuthenticationContext;
use Aztech\Rpc\Auth\AuthenticationLevel;
use Aztech\Rpc\Auth\AuthenticationStrategyProvider;
use Aztech\Rpc\Auth\AuthenticationStrategy;
use Aztech\Rpc\Auth\NullVerifier;
use Aztech\Rpc\Pdu\ConnectionOriented\BindContext;
use Aztech\Rpc\Pdu\ConnectionOriented\BindPdu;
use Aztech\Rpc\ResponseHandler\ConnectionOrientedHandler;
use Aztech\Rpc\Socket\Socket;

class Client
{

    private $authProvider = null;

    private $authContext = null;

    private $authStrategy = null;

    private $dataFormat = null;

    private $handler;

    private $socket = null;

    public function __construct(Socket $socket, AuthenticationStrategyProvider $authProvider)
    {
        $this->authProvider = $authProvider;
        $this->dataFormat = new DataRepresentationFormat();
        $this->socket = $socket;

        $this->setAuthenticationStrategy($authProvider->getStrategy());
    }

    private function generateContextId()
    {
        srand(time());

        return rand(0, bcpow(2, 16)) + rand(0, bcpow(2, 32));
    }

    public function getAuthenticationContext()
    {
        return $this->authContext;
    }

    public function getAuthenticationProvider()
    {
        return $this->authProvider;
    }

    /**
     *
     * @return AuthenticationStrategy
     */
    public function getAuthenticationStrategy()
    {
        return $this->authStrategy;
    }

    private function setAuthenticationStrategy(AuthenticationStrategy $strategy)
    {
        $contextId = $this->generateContextId();

        $this->authStrategy = $strategy;
        $this->authContext = new AuthenticationContext($contextId, AuthenticationLevel::CONNECT, $strategy->getType());
        $this->handler = new ConnectionOrientedHandler($this, $strategy);
    }

    public function setDataRepresentationFormat(DataRepresentationFormat $format)
    {
        $this->format = $format;
    }

    public function getSocket()
    {
        return $this->socket;
    }

    public function bind(BindContext $context)
    {
        $verifier = $context->isAuthDisabled() ?
            new NullVerifier() :
            $this->authStrategy->getVerifier(PduType::BIND, $this->authContext);

        $request = new BindPdu($context, $verifier);
        $request->setCallId($context->getCallId());
        $this->request($request);

        $response = $this->response();

        return $this->handler->handleResponseForBind($request, $response);
    }

    public function requestResponse(ProtocolDataUnit $request)
    {
        $this->request($request);

        $response = $this->response();

        return $this->handler->handleResponse($request, $response);
    }

    public function request(ProtocolDataUnit $request)
    {
        $this->socket->getWriter()->writePdu($request);
    }

    public function response()
    {
        return $this->socket->getReader()->readNextPdu();
    }
}
