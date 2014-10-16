<?php

namespace Aztech\Ntlm\Message;

use Aztech\Ntlm\Message;
use Aztech\Ntlm\NTLMSSP;
use Aztech\Util\Hash;
use Aztech\Util\Text;
use Aztech\Net\PacketWriter;
use Aztech\Net\ByteOrder;
use Aztech\Net\Buffer\BufferWriter;
use Aztech\Ntlm\NtlmHash;
use Aztech\Ntlm\ServerChallengeResponse;
use Aztech\Ntlm\Session;
use Aztech\Ntlm\ClientSession;
use Aztech\Ntlm\Credentials;

class AuthMessage implements Message
{

    private $challengeResponse;

    private $credentials;

    private $flags;

    public function __construct(Credentials $credentials, ServerChallengeResponse $challengeResponse, $flags)
    {
        $this->challengeResponse = $challengeResponse;
        $this->credentials = $credentials;
        $this->flags = $flags;
    }

    public function getType()
    {
        return NTLMSSP::MSG_AUTH;
    }

    public function getContent($offset)
    {
        $content = '';
        $headers = '';

        $builder = new SecurityBufferedContentBuilder();
        $sessionBuilder = new SecurityBufferedContentBuilder(false);
        $keyBuilder = new SecurityBufferedContentBuilder();

        $builder->add($this->challengeResponse->getLmChallengeResponse());
        $builder->add($this->challengeResponse->getNtlmChallengeResponse());

        $sessionBuilder->add($this->credentials->getUserDomain(), true);
        $sessionBuilder->add($this->credentials->getUser(), true);
        $sessionBuilder->add($this->credentials->getWorkstation(), true);

        $keyBuilder->add($this->challengeResponse->getEncryptedSessionKey());

        $sessionHeader = $sessionBuilder->getHeaders($offset += 20 + strlen($builder->getHeaders(0)));
        $sessionContent = $sessionBuilder->getContent($offset);

        $header = $builder->getHeaders($offset += strlen($sessionContent) + strlen($keyBuilder->getHeaders(0)));
        $content = $builder->getContent($offset);

        $keyHeader = $keyBuilder->getHeaders($offset += strlen($content) + 8);
        $key = $keyBuilder->getContent($offset);

        $writer = new BufferWriter();

        $writer->write($header);
        $writer->write($sessionHeader);
        $writer->write($keyHeader);

        $writer->writeUInt32($this->flags, ByteOrder::LITTLE_ENDIAN);
        // Version info (fake sig for now)
        $writer->writeUInt8(5);
        $writer->writeUInt8(1);
        $writer->writeUInt16(2600);
        $writer->writeInt16(0);
        $writer->writeInt8(0);
        $writer->writeUInt8(15);

        $writer->write($sessionContent);
        $writer->write($content);
        $writer->write($key);

        return $writer->getBuffer();
    }
}
