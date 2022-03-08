<?php

namespace Xuedi\PhpSysMon\Service\TemperatureProvider;

use Xuedi\PhpSysMon\LinuxPath;

class Hdd implements TemperatureProvider
{
    public function getTemperature(LinuxPath $device): int
    {
        $command = "sudo hddtemp --unit=C --wake-up --numeric " . $device->asString();

        return shell_exec($command);
    }
}
