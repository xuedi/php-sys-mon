<?php

namespace Xuedi\PhpSysMon;

class Storage
{
    private string $name;
    private string $partition;
    private array $hardDrives;
    private LinuxPath $mount;
    private FilesystemType $fsType;

    public static function fromParameters(string $name, LinuxPath $mount, string $partition, FilesystemType $fsType, array $hardDrives): self
    {
        return new self($name, $mount, $partition, $fsType, $hardDrives);
    }

    private function __construct(string $name, LinuxPath $mount, string $partition, FilesystemType $fsType, array $hardDrives)
    {
        $this->name = $name;
        $this->mount = $mount;
        $this->partition = $partition;
        $this->fsType = $fsType;
        $this->hardDrives = $this->buildHardDrives($hardDrives);
    }

    private function buildHardDrives(array $disks): array
    {
        $hardDrives = [];
        foreach ($disks as $device => $type) {
            $hardDrives[] = HardDrive::fromParameters(
                LinuxPath::fromString($device),
                HardDriveType::fromString($type)
            );
        }

        return $hardDrives;
    }

    // ########################################################################3

    public function getName(): string
    {
        return $this->name;
    }

    public function getMount(): LinuxPath
    {
        return $this->mount;
    }

    public function getPartition(): string
    {
        return $this->partition;
    }

    public function getFsType(): FilesystemType
    {
        return $this->fsType;
    }

    public function getHardDrives(): array
    {
        return $this->hardDrives;
    }
}
