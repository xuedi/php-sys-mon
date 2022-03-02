<?php

namespace Xuedi\PhpSysMon\Service\TemperatureProvider;

use Xuedi\PhpSysMon\LinuxDevice;

class Hdd implements TemperatureProvider
{
    public function getTemperature(LinuxDevice $device): int
    {
        $command = "sudo hddtemp --unit=C --wake-up --numeric " . $device->asString();

        return shell_exec($command);
    }
}