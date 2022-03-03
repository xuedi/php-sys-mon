<?php

namespace Xuedi\PhpSysMon\Service;

use Symfony\Component\DependencyInjection\Container;
use Xuedi\PhpSysMon\HardDrive;

class TemperatureService
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getHardDrive(HardDrive $hardDrive): string
    {
        $providerName = $hardDrive->getType()->getTemperatureProvider();
        $provider = $this->container->get($providerName);

        return $provider->getTemperature($hardDrive->getDevice());
    }
}
