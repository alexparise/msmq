<?php

namespace Aztech\Ntlm;

use Aztech\Ntlm\Message\Parser as MessageParser;
use Aztech\Ntlm\Message\ChallengeMessage;
use Aztech\Util\Hash;
use Aztech\Util\Text;
use Aztech\Net\PacketWriter;
use Aztech\Net\Buffer\BufferWriter;

class Client
{

    private $user;

    private $userDomain;

    private $domain;

    private $machine;

    private $lmHash;

    private $ntHash;

    private $messageFactory;

    private $messageParser;

    private $nonce;
    
    private $exportedKey;
    
    private $encryptedKey;
    
    private $sessionKey;
    
    public function __construct($user, $password, $userDomain, $domain, $machine)
    {
        $this->user = $user;
        $this->password = $password;
        $this->userDomain = $userDomain;
        $this->domain = $domain;
        $this->machine = $machine;

        $this->lmHash = Hash::hashLm($password);
        $this->ntHash = Hash::hashNt($password);

        $this->messageFactory = new MessageFactory();
        $this->messageParser = new MessageParser();
    }

    public function getNegotiatePacket()
    {
        $header = $this->getHeader(NTLMSSP::MSG_NEGOTIATE);
        $packet = $this->messageFactory->negotiate($this->domain, $this->machine);

        $message = $header . $packet->getContent(strlen($header));

        return $message;
    }

    private function getHeader($type)
    {
        return NTLMSSP::SIGNATURE . chr(0) . pack('V', $type);
    }

    public function parseChallenge($data)
    {
        return $this->messageParser->parse($data);
    }

    public function getAuthPacket(ChallengeMessage $challenge)
    {
        $this->nonce = $challenge->getNonce();
        
        $user = $this->user;
        $machine = $this->machine;
        $userDomain = $this->userDomain;
        $lmHash = $this->lmHash;
        $ntHash = $this->ntHash;

        $header = $this->getHeader(NTLMSSP::MSG_AUTH);
        $body = $this->messageFactory->authenticate($challenge, $user, $machine, $userDomain, $lmHash, $ntHash);
        $this->calculateExchangeKey($body->getFlags());
        $body->setSessionKey($this->sessionKey);
        
        $packet = $header . $body->getContent(strlen($header));

        return $packet;
    }

    private function getSessionBaseKey()
    {
        $ntResponse = Hash::calcNtlmResponse($this->ntHash, $this->nonce);
        
        return Hash::hashMd4($ntResponse);
    }
    
    public function getSessionKey()
    {
        return $this->sessionKey;
    }
    
    public function getSignature($flags)
    {
        if (! $this->encryptedKey) {
            $this->exportedKey = openssl_random_pseudo_bytes(16);
            $this->encryptedKey = Hash::encryptRc4($this->calculateExchangeKey($flags), $this->exportedKey);
        }
        
        return $this->encryptedKey;
    }
    
    public function calculateExchangeKey($flags)
    {
        if ($flags & NTLMSSP::NEGOTIATE_NTLM2) {
            throw new \BadMethodCallException();
        }
        
        $this->sessionKey = $this->kxKey($flags);
    }
    
    private function kxKey($flags)
    {
        if ($flags & NTLMSSP::NEGOTIATE_LM_KEY) {
            throw new \BadMethodCallException();
        }
        
        if ($flags & NTLMSSP::REQUEST_NON_NT_SESSION_KEY) {
            throw new \BadMethodCallException();
        }
        
        return $this->getSessionBaseKey();
    }
}
