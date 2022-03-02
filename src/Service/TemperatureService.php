<?php

namespace Xuedi\PhpSysMon\Service;

use RuntimeException;
use Xuedi\PhpSysMon\HardDrive;
use Xuedi\PhpSysMon\HardDriveType;
use Xuedi\PhpSysMon\Service\TemperatureProvider\Hdd;
use Xuedi\PhpSysMon\Service\TemperatureProvider\Nvme;
use Xuedi\PhpSysMon\Service\TemperatureProvider\Ssd;
use Xuedi\PhpSysMon\Service\TemperatureProvider\TemperatureProvider;

class TemperatureService
{
    private Hdd $hdd;
    private Ssd $ssd;
    private Nvme $nvme;

    // TODO: get DI container inside and give the harddrive type a profider string (className) to pull out of container
    public function __construct(Hdd $hdd, Ssd $ssd, Nvme $nvme)
    {
        $this->hdd = $hdd;
        $this->ssd = $ssd;
        $this->nvme = $nvme;
    }

    public function getHardDrive(HardDrive $hardDrive): string
    {
        $provider = $this->getProvider($hardDrive->getType());

        return $provider->getTemperature($hardDrive->getDevice());
    }

    private function getProvider(HardDriveType $type): TemperatureProvider
    {
        switch ($type->asString()) {
            case HardDriveType::TYPE_SSD:
                return $this->ssd;
            case HardDriveType::TYPE_HDD:
                return $this->hdd;
            case HardDriveType::TYPE_NVME:
                return $this->nvme;
            default:
                throw new RuntimeException("Could not find a TemperatureProvider for: [{$type->asString()}]");
        }
    }
}
