<?php

namespace Aztech\Dcom\Common;

use Aztech\Dcom\DcomInterface;
use Rhumsaa\Uuid\Uuid;
use Aztech\Rpc\TransferSyntax;
use Aztech\Rpc\Client;
use Aztech\Dcom\Marshalling\MarshalledBuffer;
use Aztech\Dcom\Marshalling\UnmarshallingBuffer;
use Aztech\Dcom\Marshalling\Marshaller\GuidMarshaller;
use Aztech\Util\Guid;
use Aztech\Dcom\Marshalling\Marshaller\PrimitiveMarshaller;
use Aztech\Dcom\Marshalling\Marshaller\OrpcThisMarshaller;

class ISCMActivator extends CommonInterface
{
    const IID = '{00000136-0000-0000-c000-000000000046}';

    protected $noAuth = true;
    
    protected function getIid()
    {
        return Guid::fromString(self::IID);
    }

    protected function getSyntaxes()
    {
        return [ TransferSyntax::getNdr() ];
    }

    public function remoteGetClassObject(Uuid $clsId, array $iids)
    {
        $in = new MarshalledBuffer();
        $out = new UnmarshallingBuffer();

        $in->add(new OrpcThisMarshaller(), $this->getOrpcThis());
        $in->add(new GuidMarshaller(), $clsId);
        $in->add(PrimitiveMarshaller::UInt32(), count($iids));
        $in->add(new GuidMarshaller(), $iids);

        $this->execute($this->client, 0x03, $in, $out);

        return $out;
    }

    public function remoteCreateInstance($pUnkOuter, $pActProperties)
    {
        $in = new MarshalledBuffer();
        $out = new UnmarshallingBuffer();

        $in->add(new OrpcThisMarshaller(), $this->getOrpcThis());

        $this->execute($this->client, 0x04, $in, $out);

        return $out;
    }
}
