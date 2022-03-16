<?php

namespace Xuedi\PhpSysMon\Service\TemperatureProvider;

use Xuedi\PhpSysMon\LinuxPath;
use Xuedi\PhpSysMon\ShellWrapper;

/**
 * @psalm-suppress ForbiddenCode
 */
class Nvme implements TemperatureProvider
{
    private ShellWrapper $wrapper;

    public function __construct(ShellWrapper $wrapper)
    {
        $this->wrapper = $wrapper;
    }

    public function getTemperature(LinuxPath $device): int
    {
        $command = "sudo nvme smart-log  -o json " . $device->asString();
        $json = $this->wrapper->shell_exec($command);
        $data = json_decode($json, true);
        $fahrenheit = $data['temperature'];
        $celsius = $fahrenheit - 273.15;

        return $celsius;
    }
}
