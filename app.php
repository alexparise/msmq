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

require_once 'vendor/autoload.php';

$user = 'thibaud';
$password = 'password';
$userDomain = 'WORKGROUP';
$domain = 'WORKGROUP';
$machine = 'VIRTWIN';

$ntlmClient = new NtlmClient($user, $password, $userDomain, $domain, $machine);
$ntlmStrategy = new NtlmAuthenticationStrategy($ntlmClient);

$rpcClient = new RpcClient('192.168.50.136', 135);
$rpcClient->setAuthenticationStrategy($ntlmStrategy);

$locator = new ServiceLocator($rpcClient);
$interface = $locator->getISystemActivator();

$remoteObject = $interface->createObject(Uuid::fromString("00024500-0000-0000-C000-000000000046"));
