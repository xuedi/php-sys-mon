<?php

namespace Xuedi\PhpSysMon\Service\TemperatureProvider;

use Xuedi\PhpSysMon\LinuxPath;
use Xuedi\PhpSysMon\ShellWrapper;

/**
 * @psalm-suppress ForbiddenCode
 */
class Hdd implements TemperatureProvider
{
    private ShellWrapper $wrapper;

    public function __construct(ShellWrapper $wrapper)
    {
        $this->wrapper = $wrapper;
    }

    public function getTemperature(LinuxPath $device): int
    {
        $command = "sudo hddtemp --unit=C --wake-up --numeric " . $device->asString();

        return (int)$this->wrapper->shell_exec($command);
    }
}
