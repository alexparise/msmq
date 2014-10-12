<?php

use Aztech\Rpc\Client as RpcClient;
use Aztech\Ntlm\Client as NtlmClient;
use Aztech\Ntlm\NTLMSSP;
use Aztech\Dcom\OrpcThis;
use Rhumsaa\Uuid\Uuid;
use Aztech\Dcom\DcomRequest;
use Aztech\Rpc\BindContext;

require_once 'vendor/autoload.php';

define('DCOM_IREMOTEACTIVATION', pack('H32', 'B84A9F4D1C7DCF11861E0020AF6E7C57'));
define('DCOM_IF_VERSION', 0x00);
//define('DCOM_XFER_SYNTAX', pack('H32', '045D888AEB1CC9119FE808002B104860'));
define('DCOM_XFER_SYNTAX', pack('H32', '045D888AEB1CC9119FE808002B104860'));
define('DCOM_XFER_SYNTAX_NEG', pack('H32', '2c1cb76c129840450300000000000000'));
define('DCOM_XFER_SYNTAX_VERSION', 0x02);

define('DCOM_IOXID_RESOLVER', pack('H32', 'c4fefc9960521b10bbcb00aa0021347a'));
define('DCOM_ISYSTEMACTIVATOR', pack('H32', '1a00000000000000c000000000000046'));

$user = 'thibaud';
$password = 'password';
$userDomain = 'WORKGROUP';
$domain = 'WORKGROUP';
$machine = 'VIRTWIN';

$ntlmClient = new NtlmClient($user, $password, $userDomain, $domain, $machine);
$rpcClient = new RpcClient('192.168.50.136', 135, $ntlmClient);

$contextId = 1;
$abstractSyntax = Uuid::fromBytes(DCOM_IOXID_RESOLVER);
$abstractSyntaxVersion = DCOM_IF_VERSION;
$transferSyntax = [ [ Uuid::fromBytes(DCOM_XFER_SYNTAX), DCOM_XFER_SYNTAX_VERSION ] ];
$negotiateSyntax = [ [ Uuid::fromBytes(DCOM_XFER_SYNTAX_NEG), DCOM_XFER_SYNTAX_VERSION ] ];

$context = new BindContext();
$context->addItem($abstractSyntax, $abstractSyntaxVersion, $transferSyntax);
$context->addItem($abstractSyntax, $abstractSyntaxVersion, $negotiateSyntax);

$rpcClient->rpcInitialize($context);

$body = new DcomRequest(
    Uuid::fromString('12080200-0000-0000-C000-000000000046'),
    [
        Uuid::fromString('00000000-0000-0000-C000-000000000046')
    ]
);

$message = $rpcClient->rpcCoRequest($body->getContent(), 1, 0x03, Uuid::fromString('B84A9F4D-1C7D-CF11-861E-0020AF6E7C57'));
$response = $rpcClient->rpcRequestResponse($message);

