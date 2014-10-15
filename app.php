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

require_once 'vendor/autoload.php';

$user = 'thibaud';
$password = 'password';
$userDomain = 'WORKGROUP';
$domain = 'WORKGROUP';
$machine = 'VIRTWIN';

$ntlmClient = new NtlmClient($user, $password, $userDomain, $domain, $machine);
$ntlmStrategy = new NtlmAuthenticationStrategy($ntlmClient);

$rpcClient = new RpcClient('192.168.50.135', 135);
$rpcClient->setAuthenticationStrategy($ntlmStrategy);

$locator = new ServiceLocator($rpcClient);

//$locator->getResolver()->ResolveOxId(Uuid::fromString("00450200-0000-0000-C000-000000000046"));

$interface = $locator->getISystemActivator();

$remoteObject = $interface->remoteGetClassObject(Uuid::fromString("00450200-0000-0000-C000-000000000046"), [
	Uuid::fromString("00000000-0000-0000-C000-000000000046")
]);
