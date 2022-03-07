<?php

namespace Xuedi\PhpSysMon\Service\TemperatureProvider;

use Xuedi\PhpSysMon\LinuxDevice;

class Ssd implements TemperatureProvider
{
    public function getTemperature(LinuxDevice $device): int
    {
        $command = "sudo smartctl -A " . $device->asString() . " -j";
        $json = shell_exec($command);
        $data = json_decode($json, true);
        foreach ($data['ata_smart_attributes']['table'] as $attribute) {
            if ($attribute['id'] == 190) {
                return $attribute['raw']['value'];
            }
        }
        return 0;
    }
}
