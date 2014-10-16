<?php

namespace Aztech\Dcom;

use Aztech\Rpc\Client;
use Aztech\Dcom\Common\IOxIdResolver;
use Aztech\Rpc\Socket\Socket;
use Aztech\Net\Socket\DebugSocket;
use Aztech\Dcom\Common\ISystemActivator;
use Aztech\Dcom\Common\IRemoteActivation;
use Aztech\Dcom\Common\IRemoteSCMActivator;
use Aztech\Dcom\Common\ISCMActivator;
use Aztech\Rpc\Factory;

class ServiceLocator
{

    private $client;

    private $factory;

    private $resolver;

    public function __construct(Client $client, Factory $factory)
    {
        $this->client = $client;
        $this->factory = $factory;
        $this->resolver = new IOxIdResolver($client);
    }

    public function getResolver()
    {
        return $this->resolver;
    }

    public function getIRemoteActivation()
    {
        $client = $this->getClient();

        $activator = new IRemoteActivation($client);
        $activator->setAssociationId($this->resolver->getAssociationId());

        return $activator;
    }

    public function getISCMActivator()
    {
        $client = $this->getClient();

        $activator = new ISCMActivator($client);
        $activator->setAssociationId($this->resolver->getAssociationId());

        return $activator;
    }

    public function getBoundClient()
    {
        return $this->getClient();
    }

    protected function getClient()
    {
        $bindings = null;

        $this->resolver->serverAlive2($bindings);

        foreach ($bindings->getStringBindings() as $binding) {
            $authProvider = $this->client->getAuthenticationProvider();
            $port = 135; // FIXME
            $host = $binding->getNetworkAddress();

            if (filter_var($host, FILTER_VALIDATE_IP) === false) {
                $host = gethostbyname($host);
            }

            return $this->factory->getClient($authProvider, $host, $port);
        }

        throw new \RuntimeException('Unable to connect to service');
    }
}
