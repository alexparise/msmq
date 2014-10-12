<?php

namespace Aztech\Rpc\Pdu\ConnectionOriented;

use Aztech\Rpc\Pdu\ConnectionOrientedPdu;
use Aztech\Net\DataTypes;
use Aztech\Rpc\DataRepresentationFormat;
use Aztech\Rpc\ProtocolDataUnit;
use Aztech\Rpc\PduType;

class BindPdu extends ConnectionOrientedPdu
{

    private $context;

    private $maxTransmitFragSize = self::FRAG_SZ;

    private $maxReceiveFragSize = self::FRAG_SZ;

    private $assocGroupId = 0;

    public function __construct(BindContext $context, DataRepresentationFormat $format = null)
    {
        parent::__construct(PduType::BIND, $format);

        $this->context = $context;
    }

    public function getAuthLength()
    {
        return 0;
    }

    public function getCallId()
    {
        return 1;
    }

    public function getFragmentLength()
    {
        return 5840;
    }

    public function getHeaders()
    {
        $headers = parent::getHeaders();

        $headers->addField(DataTypes::UINT16, $this->maxTransmitFragSize);
        $headers->addField(DataTypes::UINT16, $this->maxReceiveFragSize);
        $headers->addField(DataTypes::UINT32, $this->assocGroupId);

        $headers->addField(DataTypes::UINT32, $this->context->getItemCount());

        foreach ($this->context->getItems() as $item) {
            $headers->addField(DataTypes::UINT16, $item->getContextId());
            $headers->addField(DataTypes::UINT16, $item->getTransferSyntaxCount());
            $headers->addField(DataTypes::BYTES, $item->getAbstractSyntax()->getBytes());
            $headers->addField(DataTypes::UINT32, $item->getVersion());

            foreach ($item->getTransferSyntaxes() as $transferSyntax) {
                $headers->addField(DataTypes::BYTES, $transferSyntax[0]->getBytes());
                $headers->addField(DataTypes::UINT32, $transferSyntax[1]);
            }
        }
    }
}
