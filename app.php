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
use Aztech\Dcom\EndPointEntry;
use Aztech\Dcom\Common\IRemoteActivation;
use Aztech\Dcom\Common\ISCMActivator;

require_once 'vendor/autoload.php';

$user = 'thibaud';
$password = 'password';
$domain = 'WORKGROUP';
$machine = 'VIRTWIN';
$host = '192.168.50.135';

$auth = new NtlmClient($user, $password, $domain, $domain, $machine);
$client = new RpcClient($auth, $host, 135);
$locator = new ServiceLocator($client);
//$activator = $locator->getISCMActivator();

//$epMapper = new EndPointMapper($locator->getBoundClient());
$handle = null;
$entries = null;

/*$epMapper->ept_lookup(
    0x00,
    Guid::null(),
    Guid::null(),
    500,
    $handle,
    $entries
);*/

$exporter = $locator->getIRemoteActivation()->remoteActivation(Guid::fromString('{00020812-0000-0000-C000-000000000046}'), [
    Guid::fromString(IRemoteActivation::IUNK)
], [
    0x07
]);
//$interface = $locator->createInstance(IUnknown::class);

//$interface->Visible = true;

