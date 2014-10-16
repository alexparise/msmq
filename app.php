<?php

use Aztech\Dcom\ServiceLocator;
use Aztech\Dcom\Common\EndPointMapper;
use Aztech\Dcom\Common\IRemoteActivation;
use Aztech\Ntlm\Factory as NtlmFactory;
use Aztech\Rpc\Factory;
use Aztech\Util\Guid;

require_once 'vendor/autoload.php';

$user = 'thibaud';
$password = 'password';
$domain = 'WORKGROUP';
$workstation = 'VIRTWIN';
$port = 135;

try {
    $authProvider = NtlmFactory::ntlmV1($user, $password, $domain, $workstation);
    $factory = Factory::get(getenv('VERBOSE'));

    $client = $factory->getClient($authProvider, $workstation, $port);
    $locator = new ServiceLocator($client, $factory);
    //$activator = $locator->getISCMActivator();

    /*$epMapper = new EndPointMapper($locator->getBoundClient());
    $handle = null;
    $entries = null;

    $epMapper->eptLookup(
        0x00,
        Guid::null(),
        Guid::null(),
        500,
        $handle,
        $entries
    );*/

    $exporter = $locator->getISCMActivator()->remoteGetClassObject(Guid::fromString('{00020812-0000-0000-C000-000000000046}'), [
        Guid::fromString(IRemoteActivation::IUNK)
    ], [ 0x07 ]);

    $client->getSocket()->readTimeout(50, 1);
}
catch (\Exception $ex) {
    echo "\033[31;1m" . PHP_EOL;

    echo "Caught exception : \033[33;1m" . PHP_EOL;
    echo "\t" . ($ex->getMessage() ?: "<" . get_class($ex) . ">") . PHP_EOL . PHP_EOL;
    echo "\033[0;1m";
    echo "\t" . get_class($ex) . " (" . $ex->getCode() . ")" . PHP_EOL;
    echo "\t\033[0mLine " . $ex->getLine();
    echo " in " . $ex->getFile() . PHP_EOL;

    echo "\033[31;1m" . PHP_EOL;

    echo "Stack trace : \033[0m" . PHP_EOL;
    echo "\t" . implode(PHP_EOL . "\t", explode(PHP_EOL, $ex->getTraceAsString()));

    echo PHP_EOL . PHP_EOL;
}
