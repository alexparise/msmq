<?php

use Aztech\Rpc\Client as RpcClient;
use Aztech\Ntlm\Client as NtlmClient;
use Rhumsaa\Uuid\Uuid;
use Aztech\Rpc\Pdu\ConnectionOriented\BindContext;
use Aztech\Ntlm\Rpc\NtlmAuthenticationStrategy;
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
use Aztech\Ntlm\Factory;

require_once 'vendor/autoload.php';

$user = 'thibaud';
$password = 'password';
$domain = 'WORKGROUP';
$workstation = 'VIRTWIN';

try {
    $auth = Factory::ntlmV1($user, $password, $domain, $workstation);
    $client = new RpcClient($auth, $workstation, 135);
    $locator = new ServiceLocator($client);
    //$activator = $locator->getISCMActivator();

    $epMapper = new EndPointMapper($locator->getBoundClient());
    $handle = null;
    $entries = null;

    $epMapper->ept_lookup(
        0x00,
        Guid::null(),
        Guid::null(),
        500,
        $handle,
        $entries
    );

    $exporter = $locator->getISCMActivator()->remoteGetClassObject(Guid::fromString('{00020812-0000-0000-C000-000000000046}'), [
        Guid::fromString(IRemoteActivation::IUNK)
    ]);

    $client->getSocket()->readTimeout(50, 1);
}
catch (\Exception $ex) {
    echo "\033[31;1m" . PHP_EOL;

    echo "Caught exception : \033[33;1m" . PHP_EOL;
    echo "\t" . ($ex->getMessage() ?: "<" . get_class($ex) . ">") . " (error code : " . $ex->getCode() . ")" . PHP_EOL . PHP_EOL;
    echo "\033[0m";
    echo "\t" . get_class($ex) . PHP_EOL;
    echo "\tLine " . $ex->getLine();
    echo " in " . $ex->getFile() . PHP_EOL;

    echo "\033[31;1m" . PHP_EOL;

    echo "Stack trace : \033[0m" . PHP_EOL;
    echo "\t" . implode(PHP_EOL . "\t", explode(PHP_EOL, $ex->getTraceAsString()));

    echo PHP_EOL . PHP_EOL;
}
