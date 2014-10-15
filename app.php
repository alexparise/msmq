<?php

use Aztech\Rpc\Client as RpcClient;
use Aztech\Ntlm\Client as NtlmClient;
use Rhumsaa\Uuid\Uuid;
use Aztech\Rpc\Pdu\ConnectionOriented\BindContext;
use Aztech\Rpc\Auth\NtlmAuthenticationStrategy;
use Aztech\Rpc\Pdu\ConnectionOriented\RequestPdu;
use Aztech\Rpc\PduType;
use Aztech\Dcom\DcomInterface;
use Aztech\Dcom\Common\ISystemActivator;
use Aztech\Dcom\Common\IOxIdResolver;
use Aztech\Dcom\ServiceLocator;
use Aztech\Net\DataTypes;
use Aztech\Util\Guid;
use Aztech\Dcom\Interfaces\IUnknown;
use Aztech\Dcom\Common\EndPointMapper;
use Aztech\Dcom\Common\IRemoteActivation;

require_once 'vendor/autoload.php';

$user = 'thibaud';
$password = 'password';
$domain = 'WORKGROUP';
$machine = 'VIRTWIN';

$auth = new NtlmClient($user, $password, $domain, $domain, $machine);
$client = new RpcClient($auth, $machine, 135);
$locator = new ServiceLocator($client);
//$activator = $locator->getISCMActivator();

$epMapper = new EndPointMapper($client);

$epMapper->ept_lookup(
    0x00,
    Guid::null(),
    Guid::null()
);

//$exporter = $locator->getResolver()->ResolveOxId("0x00112233aabbccdd", [ 0x07 ]);
$interface = $locator->createInstance(IUnknown::class);

//$interface->Visible = true;

