<?php

namespace Xuedi\PhpSysMon\Service\TemperatureProvider;

use Xuedi\PhpSysMon\LinuxDevice;

interface TemperatureProvider
{
    public function getTemperature(LinuxDevice $device): int;
}
