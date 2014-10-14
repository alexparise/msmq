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

    protected function execute(Client $client, $op, MarshalledBuffer $in, UnmarshallingBuffer $out)
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
        $buffer->write($in->getBytes());
        
        $request = new RequestPdu($this->iid, $op, $verifier);
        $request->setBody($buffer->getBuffer());

        $response = $client->request($request);

        return $response;
    }
}
