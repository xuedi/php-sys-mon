<?php

namespace Xuedi\PhpSysMon\Helpers;

/**
 * @codeCoverageIgnore
 */
class FilesystemWrapper
{
    public function is_dir(string $asString): bool
    {
        return is_dir($asString);
    }

    public function disk_total_space(string $path): float|false
    {
        return disk_total_space($path);
    }

    public function disk_free_space(string $asString): float|false
    {
        return disk_free_space($asString);
    }
}
