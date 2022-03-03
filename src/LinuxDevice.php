<?php

namespace Xuedi\PhpSysMon;

class LinuxDevice
{
    private string $path;

    public function __construct(string $path)
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