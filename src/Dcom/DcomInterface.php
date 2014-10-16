<?php

namespace Aztech\Dcom;

use Rhumsaa\Uuid\Uuid;
use Aztech\Rpc\Pdu\ConnectionOriented\BindContext;
use Aztech\Rpc\Client;
use Aztech\Rpc\Pdu\ConnectionOriented\RequestPdu;
use Aztech\Rpc\PduType;
use Aztech\Net\Buffer\BufferWriter;
use Aztech\Dcom\Marshalling\MarshalledBuffer;
use Aztech\Dcom\Marshalling\UnmarshallingBuffer;
use Aztech\Rpc\ProtocolDataUnit;
use Aztech\Rpc\Pdu\ConnectionOriented\ResponsePdu;
use Aztech\Net\Buffer\BufferReader;
use Aztech\Rpc\Auth\NullVerifier;
use Aztech\Rpc\Auth\AuthenticationContext;
use Aztech\Rpc\Auth\AuthenticationLevel;
use Aztech\Util\Text;
use Aztech\Rpc\Pdu\ConnectionOrientedPdu;

class DcomInterface
{

    private $callId;

    private $iid;

    private $version;

    private $transferSyntaxes;

    private $transferSyntaxVersion;

    private $binding;

    private $verifier;

    protected $associationId = 0x00;

    protected $noAuth = false;

    public function __construct(Uuid $iid, $version, array $transferSyntaxes, $associationId = 0x00)
    {
        $this->iid = $iid;
        $this->version = $version;
        $this->transferSyntaxes = $transferSyntaxes;
        $this->callId = CallIdContext::getNext();
        $this->associationId = $associationId;
    }

    public function getAssociationId()
    {
        return $this->associationId;
    }

    public function setAssociationId($id)
    {
        $this->associationId = $id;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getVersion()
    {
        return $this->version;
    }

    /**
     *
     * @param Client $client
     * @param int $opNum
     * @param MarshalledBuffer $inBuffer
     * @param UnmarshallingBuffer $out
     * @return ResponsePdu
     */
    protected function execute(Client $client, $opNum, MarshalledBuffer $inBuffer = null, UnmarshallingBuffer $outBuffer = null)
    {
        if (! $this->binding) {
            $this->bind($client);
        }

        $buffer = new BufferWriter();

        if ($inBuffer) {
            $buffer->write($inBuffer->getBytes());
        }

        $request = new RequestPdu($this->iid, $opNum, $this->verifier);
        $request->setCallId($this->callId);
        $request->setBody($buffer->getBuffer());

        $response = $client->requestResponse($request);

        if ($response && $outBuffer) {
            return $this->parseResponse($response, $outBuffer);
        }

        return $response;
    }

    protected function bind(Client $client)
    {
        $context = new BindContext($this->callId, $this->noAuth, $this->associationId);

        foreach ($this->transferSyntaxes as $transferSyntax) {
            $context->addItem($this->iid, $this->version, [ $transferSyntax ]);
        }

        $originalContext = $client->getAuthenticationContext();

        $contextId = $originalContext->getContextId();
        $authLevel = $this->noAuth ? AuthenticationLevel::NONE : $originalContext->getAuthLevel();
        $authType  = $this->noAuth ? AuthenticationContext::AUTH_NONE : $originalContext->getAuthType();

        $authContext = new AuthenticationContext($contextId, $authLevel, $authType);

        $this->binding = $client->bind($context);
        $this->associationId = $this->binding->getAssociationGroupId();

        $this->verifier = $client->getAuthenticationStrategy()->getVerifier(
            PduType::REQUEST,
            $authContext,
            $this->binding
        );
    }

    protected function parseResponse(ConnectionOrientedPdu $response, UnmarshallingBuffer $outBuffer)
    {
        $reader = new BufferReader($response->getBody(), true);
        $outBuffer->parseValues($reader);
    }
}
