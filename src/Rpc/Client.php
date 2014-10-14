<?php

namespace Aztech\Rpc;

use Aztech\Net\Socket\Socket as BaseSocket;
use Aztech\Rpc\Pdu\ConnectionOriented\BindContext;
use Aztech\Rpc\Pdu\ConnectionOriented\BindPdu;
use Aztech\Net\Socket\DebugSocket;
use Aztech\Rpc\Socket\Socket;
use Aztech\Rpc\Auth\AuthenticationContext;
use Aztech\Rpc\Auth\AuthenticationLevel;
use Aztech\Rpc\Pdu\ConnectionOriented\BindAckPdu;
use Aztech\Rpc\Pdu\ConnectionOriented\BindNackPdu;
use Aztech\Rpc\ResponseHandler\ConnectionOrientedResponseHandler;
use Aztech\Rpc\ResponseHandler\ConnectionOrientedHandler;

class Client
{

    private $authContext = null;

    private $authStrategy = null;

    private $dataFormat = null;

    private $socket = null;

    public function __construct($host, $port)
    {
        $this->dataFormat = new DataRepresentationFormat();
        $this->socket = new Socket(new DebugSocket(new BaseSocket($host, $port)));
    }

    private function generateContextId()
    {
        srand(time());

        return pack('V', rand(0, bcpow(2, 16)) + rand(0, bcpow(2, 32)));
    }

    public function getAuthenticationContext()
    {
        return $this->authContext;
    }
    
    /**
     * 
     * @return AuthenticationStrategy
     */
    public function getAuthenticationStrategy()
    {
        return $this->authStrategy;
    }
    
    public function setAuthenticationStrategy(AuthenticationStrategy $strategy)
    {
        $contextId = $this->generateContextId();

        $this->authStrategy = $strategy;
        $this->authContext = new AuthenticationContext($contextId, AuthenticationLevel::CONNECT, $strategy->getType());
    }

    public function setDataRepresentationFormat(DataRepresentationFormat $format)
    {
        $this->format = $format;
    }

    public function bind(BindContext $context)
    {
        $handler = new ConnectionOrientedHandler($this, $this->authStrategy);
        
        $verifier = $this->authStrategy->getVerifier(PduType::BIND, $this->authContext);
        $request = new BindPdu($context, $verifier);

        $response = $this->requestResponse($request);

        return $handler->handleResponse($request, $response);
    }
    
    public function requestResponse(ProtocolDataUnit $request)
    {
        $this->request($request);
        
        return $this->socket->getReader()->readNextPdu();
    }
    
    public function request(ProtocolDataUnit $request)
    {
        $this->socket->getWriter()->writePdu($request);
    }
}
