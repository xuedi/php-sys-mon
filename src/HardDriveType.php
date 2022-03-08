<?php

namespace Xuedi\PhpSysMon;

use RuntimeException;
use Xuedi\PhpSysMon\Service\TemperatureProvider\Hdd;
use Xuedi\PhpSysMon\Service\TemperatureProvider\Nvme;
use Xuedi\PhpSysMon\Service\TemperatureProvider\Ssd;

class HardDriveType
{
    const SSD = 'ssd';
    const HDD = 'hdd';
    const NVME = 'nvme';
    const PROVIDER = [
        self::SSD => Ssd::class,
        self::HDD => Hdd::class,
        self::NVME => Nvme::class,
    ];
    private string $type;

    public static function fromString(string $type): self
    {
        if (!isset(self::PROVIDER[$type])) {
            throw new RuntimeException("Unknown HardDriveType: [$type]");
        }

        return new self($type);
    }

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    public function getTemperatureProvider(): string
    {
        return self::PROVIDER[$this->type];
    }

    public function asString(): string
    {
        return $this->type;
    }

    public function isNvme(): bool
    {
        return $this->type === self::NVME;
    }

    public function isHdd(): bool
    {
        return $this->type === self::HDD;
    }

    public function isSsd(): bool
    {
        return $this->type === self::SSD;
    }
}
