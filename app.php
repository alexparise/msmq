<?php

use Aztech\Rpc\Client as RpcClient;
use Aztech\Ntlm\Client as NtlmClient;
use Rhumsaa\Uuid\Uuid;
use Aztech\Rpc\Pdu\ConnectionOriented\BindContext;
use Aztech\Rpc\Auth\NtlmAuthenticationStrategy;
use Aztech\Rpc\Pdu\ConnectionOriented\RequestPdu;
use Aztech\Rpc\PduType;
use Aztech\Dcom\DcomInterface;

require_once 'vendor/autoload.php';

define('DCOM_IREMOTEACTIVATION', pack('H32', 'B84A9F4D1C7DCF11861E0020AF6E7C57'));
define('DCOM_IF_VERSION', 0x00);
//define('DCOM_XFER_SYNTAX', pack('H32', '045D888AEB1CC9119FE808002B104860'));
define('DCOM_XFER_SYNTAX', pack('H32', '045D888AEB1CC9119FE808002B104860'));
//define('DCOM_XFER_SYNTAX_NEG', pack('H32', '2c1cb76c129840450300000000000000'));
define('DCOM_XFER_SYNTAX_VERSION', 0x02);

define('DCOM_IOXID_RESOLVER', pack('H32', 'c4fefc9960521b10bbcb00aa0021347a'));
define('DCOM_ISYSTEMACTIVATOR', pack('H32', '1a00000000000000c000000000000046'));

$user = 'thibaud';
$password = 'password';
$userDomain = 'WORKGROUP';
$domain = 'WORKGROUP';
$machine = 'VIRTWIN';

$ntlmClient = new NtlmClient($user, $password, $userDomain, $domain, $machine);
$ntlmStrategy = new NtlmAuthenticationStrategy($ntlmClient);

$rpcClient = new RpcClient('192.168.50.136', 135);
$rpcClient->setAuthenticationStrategy($ntlmStrategy);

$contextId = 1;
$abstractSyntax = Uuid::fromBytes(DCOM_IREMOTEACTIVATION);
$abstractSyntaxVersion = DCOM_IF_VERSION;
$transferSyntax = Uuid::fromBytes(DCOM_XFER_SYNTAX);
$transferSyntaxVersion = DCOM_XFER_SYNTAX_VERSION;

//$negotiateSyntax = [ [ Uuid::fromBytes(DCOM_XFER_SYNTAX_NEG), DCOM_XFER_SYNTAX_VERSION ] ];

$interface = new DcomInterface($abstractSyntax, $abstractSyntaxVersion, $transferSyntax, $transferSyntaxVersion);

$interface->createInstance($rpcClient, Uuid::fromString("00024500-0000-0000-C000-000000000046"));

/*$body = new DcomRequest(
    Uuid::fromString('12080200-0000-0000-C000-000000000046'),
    [
        Uuid::fromString('00000000-0000-0000-C000-000000000046')
    ]
);*/
/*
$message = $rpcClient->rpcCoRequest($body->getContent(), 1, 0x03, Uuid::fromString('B84A9F4D-1C7D-CF11-861E-0020AF6E7C57'));
$response = $rpcClient->rpcRequestResponse($message);*/

