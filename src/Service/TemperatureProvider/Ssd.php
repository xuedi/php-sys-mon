<?php

namespace Xuedi\PhpSysMon\Service\TemperatureProvider;

use Xuedi\PhpSysMon\Helpers\ShellWrapper;
use Xuedi\PhpSysMon\LinuxPath;

/**
 * @psalm-suppress ForbiddenCode
 */
class Ssd implements TemperatureProvider
{
    private ShellWrapper $wrapper;

    public function __construct(ShellWrapper $wrapper)
    {
        $this->wrapper = $wrapper;
    }

    public function getTemperature(LinuxPath $device): int
    {
        $command = "sudo smartctl -A " . $device->asString() . " -j";
        $json = $this->wrapper->shell_exec($command);
        $data = json_decode($json, true);
        if (!isset($data['ata_smart_attributes']['table'])) {
            return 0;
        }
        foreach ($data['ata_smart_attributes']['table'] as $attribute) {
            if ($attribute['id'] == 190) {
                return $attribute['raw']['value'];
            }
        }
        return 0;
    }
}
