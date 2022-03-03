<?php

namespace Xuedi\PhpSysMon;

use RuntimeException;

class FsType
{
    const VALID_TYPES = ['btrfs', 'ext4'];
    private string $type;

    public static function fromString(string $type): self
    {
        if (!in_array($type, self::VALID_TYPES)) {
            throw new RuntimeException("Unknown FsType: [$type]");
        }

        return new self($type);
    }

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    public function asString(): string
    {
        return $this->type;
    }
}