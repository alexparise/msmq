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
    
    private $binding;

    private $verifier;
    
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
        if (! $this->binding) {
            $context = new BindContext();
    
            $context->addItem($this->iid, $this->version, [ [
                $this->transferSyntax,
                $this->transferSyntaxVersion
            ] ]);
    
            $this->binding = $client->bind($context);
            $this->verifier = $client->getAuthenticationStrategy()->getVerifier(
                PduType::REQUEST,
                $client->getAuthenticationContext(),
                $this->binding
            );
            
            $this->orpcThis = new OrpcThis(Uuid::uuid4());  
        }

        $buffer = new BufferWriter();
        
        //$buffer->write($this->orpcThis->getContent());
        $buffer->write($in->getBytes());
        
        $request = new RequestPdu(null, $op, $this->verifier);
        $request->setBody($buffer->getBuffer());

        $response = $client->requestResponse($request);

        return $response;
    }
}
