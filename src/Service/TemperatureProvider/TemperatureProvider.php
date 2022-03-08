<?php

namespace Xuedi\PhpSysMon\Service\TemperatureProvider;

use Xuedi\PhpSysMon\LinuxPath;

interface TemperatureProvider
{
    public function getTemperature(LinuxPath $device): int;
}
