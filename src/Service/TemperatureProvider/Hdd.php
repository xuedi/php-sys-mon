<?php

namespace Xuedi\PhpSysMon\Service\TemperatureProvider;

use Xuedi\PhpSysMon\LinuxPath;

/**
 * @psalm-suppress ForbiddenCode
 */
class Hdd implements TemperatureProvider
{
    public function getTemperature(LinuxPath $device): int
    {
        $command = "sudo hddtemp --unit=C --wake-up --numeric " . $device->asString();

        return (int)shell_exec($command);
    }
}
