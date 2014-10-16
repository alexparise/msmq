<?php

namespace Aztech\Rpc\Socket;

use Aztech\Net\Socket\SocketWriter as BaseSocketWriter;
use Aztech\Rpc\ProtocolDataUnit;
use Aztech\Rpc\PduFieldDefinition;
use Aztech\Net\DataTypes;
use Aztech\Net\Writer;
use Aztech\Net\Buffer\BufferWriter;
use Aztech\Rpc\ProtocolDataUnitVisitor;
use Aztech\Rpc\Pdu\ConnectionOriented\BindPdu;
use Aztech\Rpc\Pdu\ConnectionOriented\BindAckPdu;
use Aztech\Rpc\Pdu\ConnectionOriented\BindResponsePdu;
use Aztech\Rpc\Pdu\ConnectionOrientedPdu;
use Aztech\Rpc\Rpc;
use Aztech\Rpc\PduFieldCollection;
use Aztech\Rpc\Pdu\ConnectionOriented\RequestPdu;

class SocketWriter implements ProtocolDataUnitVisitor
{

    const AUTH_HEADER_SIZE = 8;

    const HEADER_SIZE = 16;

    const RESP_HEADER_SIZE = 8;

    const FRAG_SZ = 5840;

    private $socketWriter;

    private $buffer;

    private $packetSize;

    private $authSize;

    public function __construct(BaseSocketWriter $socketWriter)
    {
        $this->socketWriter = $socketWriter;
    }

    public function writePdu(ProtocolDataUnit $pdu)
    {
        $this->buffer = new BufferWriter();

        $pdu->accept($this);

        $this->socketWriter->write($this->buffer->getBuffer());
    }

    public function visitBind(BindPdu $pdu)
    {
        $fields = new PduFieldCollection();

        $this->appendCommonHeaders($fields, $pdu);
        $this->appendCommonBindHeaders($fields, $pdu);
        $this->appendBindHeaders($fields, $pdu);

        $this->finalizeWrite($fields, $pdu);
    }

    public function visitBindAck(BindAckPdu $pdu)
    {

    }

    public function visitBindResponse(BindResponsePdu $pdu)
    {
        $fields = new PduFieldCollection();

        $this->appendCommonHeaders($fields, $pdu);

        $fields->addField(DataTypes::UINT16, self::FRAG_SZ);
        $fields->addField(DataTypes::UINT16, self::FRAG_SZ);

        $this->finalizeWrite($fields, $pdu);
    }

    public function visitRequest(RequestPdu $pdu)
    {
        $fields = new PduFieldCollection();

        $this->appendCommonHeaders($fields, $pdu);

        $fields->addField(DataTypes::UINT32, strlen($pdu->getBody()));
        $fields->addField(DataTypes::UINT16, $pdu->getContextId());
        $fields->addField(DataTypes::UINT16, $pdu->getOpNum());
        if ($pdu->getObject()) {
            $fields->addField(DataTypes::BYTES, $pdu->getObject()->getBytes());
        }
        $fields->addField(DataTypes::BYTES, $pdu->getBody());

        $this->finalizeWrite($fields, $pdu);
    }

    protected function appendCommonHeaders(PduFieldCollection $headers, ConnectionOrientedPdu $pdu)
    {
        $headers->addField(DataTypes::UINT8, Rpc::VERSION_MAJOR);
        $headers->addField(DataTypes::UINT8, Rpc::VERSION_MINOR);
        $headers->addField(DataTypes::UINT8, $pdu->getType());
        $headers->addField(DataTypes::UINT8, $pdu->getFlags());
        $headers->addField(DataTypes::UINT32, $pdu->getFormat()->getValue());
        $headers->addField(DataTypes::UINT16, function () { return $this->packetSize; });
        $headers->addField(DataTypes::UINT16, function () { return $this->authSize; });
        $headers->addField(DataTypes::UINT32, $pdu->getCallId());
    }

    protected function appendCommonBindHeaders(PduFieldCollection $headers, ConnectionOrientedPdu $pdu)
    {
        if (! ($pdu instanceof BindAckPdu) && ! ($pdu instanceof BindPdu)) {
            return;
        }

        $headers->addField(DataTypes::UINT16, self::FRAG_SZ);
        $headers->addField(DataTypes::UINT16, self::FRAG_SZ);
        $headers->addField(DataTypes::UINT32, $pdu->getAssociationGroupId());
    }

    protected function appendBindHeaders(PduFieldCollection $headers, BindPdu $pdu)
    {
        $headers->addField(DataTypes::UINT32, $pdu->getContext()->getItemCount());

        foreach ($pdu->getContext()->getItems() as $item) {
            $headers->addField(DataTypes::UINT16, $item->getContextId());
            $headers->addField(DataTypes::UINT16, $item->getTransferSyntaxCount());
            $headers->addField(DataTypes::BYTES, $item->getAbstractSyntax()->getBytes());
            $headers->addField(DataTypes::UINT32, $item->getVersion());

            foreach ($item->getTransferSyntaxes() as $transferSyntax) {
                $headers->addField(DataTypes::BYTES, $transferSyntax->getUuid()->getBytes());
                $headers->addField(DataTypes::UINT32, $transferSyntax->getVersion());
            }
        }
    }

    protected function finalizeWrite(PduFieldCollection $fields, ProtocolDataUnit $pdu)
    {
        $authHeaders = $this->generateAuthHeaders($fields, $pdu);
        $authBody = $pdu->getVerifier()->getContent();

        $this->authSize = $authBody->getSize();
        $this->packetSize = $fields->getSize() + $authHeaders->getSize() + $this->authSize;

        $this->writeFields($fields);
        $this->writeFields($authHeaders);
        $this->writeFields($authBody);
    }

    protected function generateAuthHeaders(PduFieldCollection $fields, ProtocolDataUnit $pdu)
    {
        $padding = 0;

        while ($fields->getSize() % 4 != 0) {
            $fields->addField(DataTypes::UINT8, 0);
            $padding++;
        }

        return $pdu->getVerifier()->getHeaders($padding);
    }

    protected function writeFields(PduFieldCollection $fields)
    {
        foreach ($fields->getFields() as $field) {
            $this->writeField($this->buffer, $field);
        }
    }

    protected function writeBody(ProtocolDataUnit $pdu)
    {
        $this->buffer->write($pdu->getBody());
    }

    protected function writeAuth(ProtocolDataUnit $pdu)
    {
        $writer = $this->buffer;
        $authPad = 0;

        if (! count($pdu->getVerifier()->getContent()->getFields())) {
            return;
        }

        while ($writer->getBufferSize() % 4 != 0) {
            $writer->writeChr(0);
            $authPad++;
        }

        foreach ($pdu->getVerifier()->getHeaders($authPad)->getFields() as $field) {
            $this->writeField($writer, $field);
        }

        foreach ($pdu->getVerifier()->getContent()->getFields() as $field) {
            $this->writeField($writer, $field);
        }
    }

    public function writeField(Writer $writer, PduFieldDefinition $field)
    {
        switch ($field->getType()) {
            case DataTypes::INT8:
                return $writer->writeInt8($field->getValue());
            case DataTypes::INT16:
                return $writer->writeInt16($field->getValue());
            case DataTypes::INT32:
                return $writer->writeInt32($field->getValue());
            case DataTypes::INT64:
                return $writer->writeInt64($field->getValue());
            case DataTypes::UINT8:
                return $writer->writeUInt8($field->getValue());
            case DataTypes::UINT16:
                return $writer->writeUInt16($field->getValue());
            case DataTypes::UINT32:
                return $writer->writeUInt32($field->getValue());
            case DataTypes::UINT64:
                return $writer->writeUInt64($field->getValue());
            case DataTypes::BYTES:
            default:
                return $writer->write($field->getValue());
        }
    }
}
