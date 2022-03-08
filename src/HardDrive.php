<?php

namespace Xuedi\PhpSysMon;

class HardDrive
{
    private LinuxPath $device;
    private HardDriveType $type;

    public static function fromParameters(LinuxPath $device, HardDriveType $type): self
    {
        return new self($device, $type);
    }

    private function __construct(LinuxPath $device, HardDriveType $type)
    {
        $this->device = $device;
        $this->type = $type;
    }

    public function getDevice(): LinuxPath
    {
        return $this->device;
    }

    public function getType(): HardDriveType
    {
        return $this->type;
    }
}
