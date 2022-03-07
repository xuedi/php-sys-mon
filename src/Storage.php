<?php

namespace Xuedi\PhpSysMon;

class Storage
{
    private string $name;
    private string $mount;
    private string $partition;
    private FsType $fsType;
    private array $hardDrives;

    public function __construct(string $name, array $data)
    {
        $this->name = $this->verifyName($name);
        $this->mount = $this->verifyMount($data['mount']);
        $this->partition = $this->verifyPartition($data['partition']);
        $this->fsType = $this->verifyFsType($data['fsType']);
        $this->hardDrives = $this->verifyHardDrives($data['disks']);
    }

    private function verifyName(string $name): string
    {
        return $name;
    }

    private function verifyMount(string $mount): string
    {
        return $mount;
    }

    private function verifyPartition(string $partition): string
    {
        return $partition;
    }

    private function verifyFsType(string $fsType): FsType
    {
        return FsType::fromString($fsType);
    }

    private function verifyHardDrives(array $disks): array
    {
        $hardDrives = [];
        foreach ($disks as $device => $data) {
            $type = $data['type'] ?? 'unknown';
            $model = $data['model'] ?? 'unknown';
            $hardDrives[] = new HardDrive(
                new LinuxDevice($device),
                new HardDriveType($type),
                new HardDriveModel($model)
            );
        }

        return $hardDrives;
    }

    // ########################################################################3

    public function getName(): string
    {
        return $this->name;
    }

    public function getMount(): string
    {
        return $this->mount;
    }

    public function getPartition(): string
    {
        return $this->partition;
    }

    public function getFsType(): FsType
    {
        return $this->fsType;
    }

    public function getHardDrives(): array
    {
        return $this->hardDrives;
    }
}
