<?php

namespace Xuedi\PhpSysMon;

use RuntimeException;

class LinuxPath
{
    private string $path;

    public static function fromString(string $path): self
    {
        if (empty($path)) {
            throw new RuntimeException("LinuxDevice cant be empty");
        }
        if ($path[0] !== '/') {
            throw new RuntimeException("LinuxDevice must start with a slash");
        }
        if (str_ends_with($path, '/') && $path !== '/') {
            throw new RuntimeException("LinuxDevice cant have a tailing slash");
        }
        return new self($path);
    }

    private function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getId(): string
    {
        return md5($this->path);
    }

    public function asString(): string
    {
        return $this->path;
    }
}
