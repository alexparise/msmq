<?php

namespace Aztech\Rpc;

use Aztech\Net\AbstractReader;
use Aztech\Net\ByteOrder;
use Aztech\Net\PacketReader;
use Aztech\Net\PacketWriter;
use Aztech\Net\Writer;
use Aztech\Net\Socket\DebugSocket;
use Aztech\Ntlm\Client as NtlmClient;
use Aztech\Ntlm\NTLMSSP;
use Aztech\Ntlm\Message\ChallengeMessage;
use Aztech\Util\Text;
use Aztech\Rpc\Request\BindRequest;
use Aztech\Rpc\Request\BindResponseRequest;
use Aztech\Rpc\Request\CoRequest;
use Rhumsaa\Uuid\Uuid;

class Client
{

    private $ntlmClient;

    private $socket;

    private $contextId;

    private $authValue;

    private $authContext;

    public function __construct($host, $port, NtlmClient $ntlmClient)
    {
        $this->ntlmClient = $ntlmClient;
        $this->socket = new DebugSocket(new Socket($host, $port));

        $contextId = $this->generateContextId();
        $this->authContext = new AuthenticationContext($contextId, Rpc::AUTH_LEVEL_CONNECT, Rpc::AUTH_NTLM);
    }

    private function generateContextId()
    {
        srand(time());

        return pack('V', rand(0, bcpow(2, 16)) + rand(0, bcpow(2, 32)));
    }

    public function rpcRequest(Request $request)
    {
        $writer = $this->socket->getWriter();
        $writer->write($request->getContent());
    }

    public function rpcResponse()
    {
        $reader = new SocketReader($this->socket);

        return $reader->readResponse();
    }

    public function rpcRequestResponse(Request $message)
    {
        $this->rpcRequest($message);

        return $this->rpcResponse();
    }

    /**
     * Runs bind, bindAck, bindResp
     */
    public function rpcInitialize(BindContext $context)
    {
        $query = $this->rpcBind($context);

        $response = $this->rpcRequestResponse($query);
        $header = $response->getHeader();

        $this->authValue = $response->getBytes()->readFrom($header->getPacketSize() - $header->getAuthSize() + 20);
        $this->challenge = $this->ntlmClient->parseChallenge($this->authValue);

        $query = $this->rpcBindResponse($this->challenge);
        $this->rpcRequest($query);
    }

    public function rpcBind(BindContext $context)
    {
        $bindRequest = new BindRequest($context);
        $auth = new PacketReader($this->ntlmClient->getNegotiatePacket());

        return new AuthenticatedRequest($bindRequest, $this->authContext, $auth);
    }

    public function rpcBindResponse()
    {
        $request = new BindResponseRequest();
        $auth = new PacketReader($this->ntlmClient->getAuthPacket($this->challenge));

        return new AuthenticatedRequest($request, $this->authContext, $auth);
    }

    public function rpcCoRequest($body, $contextId, $opNum, Uuid $uuid)
    {
        $request = new CoRequest($body, $contextId, $opNum, $uuid);
        $auth = new PacketReader($this->ntlmClient->getAuthVerifier());

        return new AuthenticatedRequest($request, $this->authContext, $auth);
    }

}
