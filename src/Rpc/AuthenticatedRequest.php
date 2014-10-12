<?php

namespace Aztech\Rpc;

use Aztech\Net\Writer;
use Aztech\Net\PacketWriter;
use Aztech\Net\PacketReader;

class AuthenticatedRequest implements Request
{

    private $auth;

    private $authContext;

    private $request;

    public function __construct(Request $request, AuthenticationContext $authContext, PacketReader $auth)
    {
        $this->auth = $auth;
        $this->authContext = $authContext;
        $this->request = $request;
    }

    public function getType()
    {
        return $this->request->getType();
    }

    public function getContent()
    {
        $content = new PacketWriter();
        $authPad = 0;

        $content->write($this->request->getContent());

        while ($content->getBufferSize() % 4 != 0) {
            $content->writeChr(0);
            $authPad++;
        }

        $this->writeAuth($content, $authPad);

        $writer = new PacketWriter();

        $writer->write($this->getHeader($content)->getBytes());
        $writer->write($content->getBuffer());
        $writer->write($this->auth->getBuffer());

        return $writer->getBuffer();
    }

    public function getFlags()
    {
        return Rpc::PFC_FIRST_FRAG | Rpc::PFC_LAST_FRAG | $this->request->getFlags();
    }

    private function writeAuth(Writer $writer, $authPad)
    {
        $writer->writeChr($this->authContext->getAuthType());
        $writer->writeChr($this->authContext->getAuthLevel());
        $writer->writeChr($authPad);
        $writer->writeChr(0);
        $writer->write($this->authContext->getContextId());
    }

    private function getHeader($writer)
    {
        $type = $this->request->getType();
        $flags = $this->getFlags();
        $bufferSize = $writer->getBufferSize();
        $authSize = strlen($this->auth->getBuffer());

        return new ConnectionOrientedHeader($type, $flags, $bufferSize, $authSize);
    }
}
