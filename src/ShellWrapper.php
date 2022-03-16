<?php

namespace Xuedi\PhpSysMon;

/**
 * @codeCoverageIgnore
 */
class ShellWrapper
{
    public function shell_exec(string $command): string
    {
        return shell_exec($command) ?? '';
    }
}
