<?php

namespace Xuedi\PhpSysMon\Service\TemperatureProvider;

use Xuedi\PhpSysMon\LinuxPath;

class Nvme implements TemperatureProvider
{
    public function getTemperature(LinuxPath $device): int
    {
        $command = "sudo nvme smart-log  -o json " . $device->asString();
        $json = shell_exec($command);
        $data = json_decode($json, true);
        $fahrenheit = $data['temperature'];
        $celsius = $fahrenheit - 273.15;

        return $celsius;
    }
}
