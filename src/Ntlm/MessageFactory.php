<?php

namespace Aztech\Ntlm;

use Aztech\Ntlm\Message\AuthMessage;
use Aztech\Ntlm\Message\ChallengeMessage;
use Aztech\Ntlm\Message\NegotiateMessage;
use Aztech\Util\Text;

class MessageFactory
{

    /**
     * Returns an auth negotiation message that clients can use to negotiate with the auth server.
     *
     * @param string|null $callingDomain
     * @param string|null $callingMachine
     * @return \Aztech\Ntlm\Message\NegotiateMessage
     */
    public function negotiate(ClientSession $session)
    {
        $credentials = $session->getCredentials();
        $flags = $session->getNegotiationFlags();

        return new NegotiateMessage($credentials, $flags);
    }

    /**
     * Returns an auth message generated in response to a server challenge.
     *
     * @param Session $session
     * @param ChallengeMessage $challenge
     * @param int $serverFlags The negotiated authentication flags that the server has accepted.
     *
     * @return AuthMessage
     */
    public function authenticate(ClientSession $session, ChallengeMessage $challenge)
    {
        $credentials = $session->getCredentials();
        $flags = $session->getNegotiationFlags() & $challenge->getFlags();
        $response = $session->generateChallengeResponse($challenge->getChallenge());

        return new AuthMessage($credentials, $response, $flags);
    }
}
