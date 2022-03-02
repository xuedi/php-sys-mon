<?php

namespace Xuedi\PhpSysMon;

use RuntimeException;

class HardDriveType
{
    const TYPE_SSD = 'ssd';
    const TYPE_HDD = 'hdd';
    const TYPE_NVME = 'nvme';
    const VALID_TYPES = [
        self::TYPE_SSD,
        self::TYPE_HDD,
        self::TYPE_NVME,
    ];
    private string $type;

    public function __construct(string $type)
    {
        if (!in_array($type, self::VALID_TYPES)) {
            throw new RuntimeException("Unknown HardDriveType: [$type]");
        }
        $this->type = $type;
    }

    public function asString(): string
    {
        return $this->type;
    }

    public function isNvme(): bool
    {
        return $this->type === self::TYPE_NVME;
    }

    public function isHdd(): bool
    {
        return $this->type === self::TYPE_HDD;
    }

    public function isSsd(): bool
    {
        return $this->type === self::TYPE_SSD;
    }
}