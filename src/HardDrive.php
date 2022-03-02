<?php

namespace Xuedi\PhpSysMon;

class HardDrive
{
    private LinuxDevice $device;
    private HardDriveType $type;
    private HardDriveModel $model;

    public function __construct(LinuxDevice $device, HardDriveType $type, HardDriveModel $model)
    {
        $this->device = $device;
        $this->model = $model;
        $this->type = $type;
    }

    public function getDevice(): LinuxDevice
    {
        return $this->device;
    }

    public function getModel(): HardDriveModel
    {
        return $this->model;
    }

    public function getType(): HardDriveType
    {
        return $this->type;
    }

}