<?php

namespace Xuedi\PhpSysMon\Service;

use Symfony\Component\DependencyInjection\Container;
use Xuedi\PhpSysMon\HardDrive;

class TemperatureService
{
    private Container $container;
    private array $cache;

    public function __construct(Container $container)
    {
        $this->cache = [];
        $this->container = $container;
    }

    public function measure(HardDrive $hardDrive): string
    {
        $device = $hardDrive->getDevice();
        if (isset($this->cache[$device->getId()])) {
            return $this->cache[$device->getId()];
        }
        $providerName = $hardDrive->getType()->getTemperatureProvider();
        $provider = $this->container->get($providerName);
        $temp = $provider->getTemperature($hardDrive->getDevice());
        $this->cache[$device->getId()] = $temp;

        return $temp;
    }
}
