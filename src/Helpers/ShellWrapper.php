<?php

namespace Xuedi\PhpSysMon\Helpers;

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
