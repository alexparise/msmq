<?php

namespace Aztech\Dcom\Common;

use Aztech\Dcom\DcomInterface;
use Aztech\Util\Guid;
use Aztech\Rpc\TransferSyntax;
use Aztech\Dcom\Marshalling\MarshalledBuffer;
use Aztech\Dcom\Marshalling\Marshaller\PrimitiveMarshaller;
use Rhumsaa\Uuid\Uuid;
use Aztech\Dcom\Marshalling\Marshaller\GuidMarshaller;
use Aztech\Rpc\Client;

class EndPointMapper extends DcomInterface
{
    const IID = '{e1af8308-5d1f-11c9-91a4-08002b14a0fa}';

    private $client;

    public function __construct(Client $client)
    {
        parent::__construct(Guid::fromString(self::IID), 0x03, [ TransferSyntax::getNdr() ]);


        $this->noAuth = true;
        $this->client = $client;
    }

    public function ept_lookup($type, Uuid $service, UUid $interface)
    {
        $in = new MarshalledBuffer();

        $in->add(PrimitiveMarshaller::UInt32(), $type);
        $in->add(PrimitiveMarshaller::UInt32(), 1);
        $in->add(new GuidMarshaller(), $service);
        $in->add(PrimitiveMarshaller::UInt32(), 2);
        $in->add(new GuidMarshaller(), $interface);
        $in->add(PrimitiveMarshaller::UInt16(), 2);
        $in->add(PrimitiveMarshaller::UInt16(), 0);
        $in->add(PrimitiveMarshaller::UInt32(), 0);
        $in->add(new GuidMarshaller(), null);
        $in->add(PrimitiveMarshaller::UInt32(), 0);
        $in->add(PrimitiveMarshaller::UInt32(), 500);

        return $this->execute($this->client, 0x02, $in);
    }
}
