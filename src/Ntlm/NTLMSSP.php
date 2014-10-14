<?php

namespace Aztech\Ntlm;

/**
 * Port of Perl's NTLM auth module.
 *
 * @author thibaud
 * @link http://cpansearch.perl.org/src/UMVUE/Authen-Perl-NTLM-0.12/lib/Authen/Perl/NTLM.pm
 * @link http://davenport.sourceforge.net/ntlm.html
 */
class NTLMSSP
{

    const SIGNATURE                             = 'NTLMSSP';

    const MSG_NEGOTIATE                         = 1;

    const MSG_CHALLENGE                         = 2;

    const MSG_AUTH                              = 3;

    const MSG_UNKNOWN                           = 4;

    const NEGOTIATE_UNICODE                     = 0x00000001;

    const NEGOTIATE_OEM                         = 0x00000002;

    const REQUEST_TARGET                        = 0x00000004;

    const NEGOTIATE_SIGN                        = 0x00000010;

    const NEGOTIATE_SEAL                        = 0x00000020;

    const NEGOTIATE_DATAGRAM                    = 0x00000040;

    const NEGOTIATE_LM_KEY                      = 0x00000080;

    const NEGOTIATE_NETWARE                     = 0x00000100;

    const NEGOTIATE_NTLM                        = 0x00000200;

    const NEGOTIATE_OEM_DOMAIN_SUPPLIED         = 0x00001000;

    const NEGOTIATE_OEM_WORKSTATION_SUPPLIED    = 0x00002000;

    const NEGOTIATE_LOCAL_CALL                  = 0x00004000;

    const NEGOTIATE_ALWAYS_SIGN                 = 0x00008000;

    const TARGET_TYPE_DOMAIN                    = 0x00010000;

    const TARGET_TYPE_SERVER                    = 0x00020000;

    const TARGET_TYPE_SHARE                     = 0x00040000;

    const NEGOTIATE_NTLM2                       = 0x00080000;

    const REQUEST_INIT_RESPONSE                 = 0x00100000;

    const REQUEST_ACCEPT_RESPONSE               = 0x00200000;

    const REQUEST_NON_NT_SESSION_KEY            = 0x00400000;

    const NEGOTIATE_TARGET_INFO                 = 0x00800000;

    const RESERVED4                             = 0x01000000;

    const NEGOTIATE_VERSION                     = 0x02000000;

    const RESERVED3                             = 0x04000000;

    const RESERVED2                             = 0x08000000;

    const RESERVED1                             = 0x10000000;

    const NEGOTIATE_128                         = 0x20000000;

    const NEGOTIATE_KEY_EXCH                    = 0x40000000;

    /**
     * @deprecated Use NEGOTIATE_56
     * @var unknown
     */
    const NEGOTIATE_80000000                    = 0x80000000;
    const NEGOTIATE_56                          = 0x80000000;
}
