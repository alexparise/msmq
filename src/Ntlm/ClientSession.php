<?php

namespace Aztech\Ntlm;

interface ClientSession
{

    /**
     * @return Credentials
     */
    public function getCredentials();

    /**
     * @return int
     */
    public function getNegotiationFlags();

    /**
     * @return string
     */
    public function getSessionKey();

    /**
     *
     * @param byte[16] $randomSessionKey
     * @return ClientSession
     */
    public function fork($randomSessionKey);

    /**
     *
     * @param ServerChallenge $challenge
     * @return ServerChallengeResponse
     */
    public function generateChallengeResponse(ServerChallenge $challenge);
}
