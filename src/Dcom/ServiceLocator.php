<?php

namespace Aztech\Dcom;

use Aztech\Rpc\Client;
use Aztech\Dcom\Common\IOxIdResolver;
use Aztech\Rpc\Socket\Socket;
use Aztech\Net\Socket\DebugSocket;
use Aztech\Dcom\Common\ISystemActivator;
use Aztech\Dcom\Common\IRemoteActivation;

class ServiceLocator
{

    private $client;

    private $resolver;

    public function __construct(Client $client)
    {
        $this->client = $client;
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
    
    public function getISystemActivator()
    {
        $client = $this->getClient();
        
        $activator = new ISystemActivator($client);
        $activator->setAssociationId($this->resolver->getAssociationId());
        
        return $activator;
    }
    
    protected function getClient()
    {
        $bindings = $this->resolver->ServerAlive2();

        foreach ($bindings->getStringBindings() as $binding) {
            $host = $binding->getNetworkAddress();

            if (filter_var($host, FILTER_VALIDATE_IP) === false || $host != $this->client->host) {
                continue;
            }

            $port = 135; // FIXME

            $client = new Client($host, $port);
            $client->setAuthenticationStrategy($this->client->getAuthenticationStrategy());

            return $client;
        }

        throw new \RuntimeException('Unable to connect to service');
    }
}
