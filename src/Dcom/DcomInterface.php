<?php

namespace Aztech\Dcom;

use Rhumsaa\Uuid\Uuid;
use Aztech\Rpc\Pdu\ConnectionOriented\BindContext;
use Aztech\Rpc\Client;
use Aztech\Rpc\Pdu\ConnectionOriented\RequestPdu;
use Aztech\Rpc\PduType;
use Aztech\Net\Buffer\BufferWriter;

class DcomInterface
{

    private $iid;

    private $version;

    private $transferSyntax;

    private $transferSyntaxVersion;

    public function __construct(Uuid $iid, $version, Uuid $transferSyntax, $transferSyntaxVersion)
    {
        $this->iid = $iid;
        $this->version = $version;
        $this->transferSyntax = $transferSyntax;
        $this->transferSyntaxVersion = $transferSyntaxVersion;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function createInstance(Client $client, Uuid $object)
    {
        $context = new BindContext();

        $context->addItem($this->iid, $this->version, [ [
            $this->transferSyntax,
            $this->transferSyntaxVersion
        ] ]);

        $binding = $client->bind($context);
        $verifier = $client->getAuthenticationStrategy()->getVerifier(
            PduType::REQUEST,
            $client->getAuthenticationContext(),
            $binding
        );

        $buffer = new BufferWriter();
        $buffer->write((new OrpcThis(Uuid::uuid4()))->getContent());

        // DCOM
        // ClsId
        $buffer->write($object->getBytes());
        // OBJREF Count ???
        $buffer->writeUInt32(0);
        // ??
        $buffer->writeUInt32(0);
        // Client imp level
        $buffer->writeUInt32(0);
        // Mode
        $buffer->writeUInt32(0);
        // IID count
        $buffer->writeUInt32(1);
        // ???
        $buffer->write(pack('H*', '803F140001000000'));

        $buffer->write($object->getBytes());

        // RequestedProtSeq
        $buffer->writeUInt32(1);
        $buffer->writeUInt32(1);
        // Type (tcp)
        $buffer->writeUInt16(7);

        $request = new RequestPdu($this->iid, 0x03, $verifier);
        $request->setBody($buffer->getBuffer());

        $response = $client->request($request);

        return $response;
    }
}
