<?php

namespace Aztech\Ntlm\Message;

use Aztech\Ntlm\Message;
use Aztech\Ntlm\NTLMSSP;
use Aztech\Util\Hash;
use Aztech\Util\Text;
use Aztech\Net\PacketWriter;
use Aztech\Net\ByteOrder;
use Aztech\Net\Buffer\BufferWriter;

class AuthMessage implements Message
{

    private $flags;

    private $nonce;

    private $userDomain;

    private $user;

    private $machine;

    private $lmHash;

    private $ntHash;
    
    private $sessionKey = '';

    public function __construct($flags, $nonce, $userDomain, $user, $machine, $lmHash, $ntHash)
    {
        $this->flags = $flags;
        $this->nonce = $nonce;
        $this->userDomain = $userDomain;
        $this->user = $user;
        $this->machine = $machine;
        $this->lmHash = $lmHash;
        $this->ntHash = $ntHash;
    }

    public function getType()
    {
        return NTLMSSP::MSG_AUTH;
    }

    public function getFlags()
    {
        return $this->flags;
    }
    
    public function getContent($offset)
    {
        $content = '';
        $headers = '';

        $lmResponse = Hash::calcNtlmResponse($this->lmHash, $this->nonce);
        $ntResponse = Hash::calcNtlmResponse($this->ntHash, $this->nonce);

        $builder = new SecurityBufferedContentBuilder();

        $builder->add($lmResponse);
        $builder->add($ntResponse);
        $builder->add($this->userDomain, true);
        $builder->add($this->user, true);
        $builder->add($this->machine, true);
        $builder->add($this->getSessionKey());

        $header = $builder->getHeaders($offset += 4);
        $content = $builder->getContent($offset);

        $writer = new BufferWriter();

        $writer->write($header);
        $writer->writeUInt32($this->flags, ByteOrder::LITTLE_ENDIAN);
        $writer->write($content);

        return $writer->getBuffer();
    }

    private function getSecurityBuffer(& $position, $value, $isUnicode = false, $forcedLength = false)
    {
        $buffer = '';
        $offset = $forcedLength;
        $multiplier = $isUnicode ? 2 : 1;

        if ($forcedLength === false) {
            $offset = 0;
            $position += strlen($value) * $multiplier;
        }

        $buffer .= $this->getPackedLength($value, $multiplier);
        $buffer .= $this->getPackedInt($position + $offset + strlen($buffer) + 4);

        return $buffer;
    }

    private function getPackedLength($value, $lengthMultiplier = 1)
    {
        return pack('v', strlen($value) * $lengthMultiplier) . pack('v', strlen($value) * $lengthMultiplier);
    }

    private function getPackedInt($value)
    {
        return pack('V', $value);
    }

    private function getSessionKey()
    {
        return $this->sessionKey;
    }
    
    public function setSessionKey($key)
    {
        $this->sessionKey = $key;
    }
}
