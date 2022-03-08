<?php

namespace Xuedi\PhpSysMon;

class HardDrive
{
    private LinuxDevice $device;
    private HardDriveType $type;

    public static function fromParameters(LinuxDevice $device, HardDriveType $type): self
    {
        return new self($device, $type);
    }

    private function __construct(LinuxDevice $device, HardDriveType $type)
    {
        $this->device = $device;
        $this->type = $type;
    }

    public function getDevice(): LinuxDevice
    {
        return $this->device;
    }

    public function getType(): HardDriveType
    {
        return $this->type;
    }
}
