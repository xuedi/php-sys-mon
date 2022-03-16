<?php

namespace Xuedi\PhpSysMon\Service;

use Xuedi\PhpSysMon\Helpers\ShellWrapper;
use Xuedi\PhpSysMon\Sensor;

class LmSensorsService
{
    private $blocks;

    public function __construct(ShellWrapper $wrapper)
    {
        $this->blocks = $this->load($wrapper);
    }

    public function read(Sensor $sensor): string
    {
        return $this->blocks[$sensor->getProvider()][$sensor->getIdent()] ?? 'X';
    }

    private function load(ShellWrapper $wrapper): array
    {
        $raw = $wrapper->shell_exec("sensors -A");
        $lines = explode("\n", $raw);

        $blocks = [];
        $blockName = '';
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($blockName) && !empty($line)) {
                $blockName = $line;
                continue;
            }
            if (empty($line)) {
                $blockName = '';
                continue;
            }
            if (str_contains($line, ':')) {
                if (str_contains($line, '(')) {
                    $line = substr($line, 0, strpos($line, '('));
                }
                list($key, $value) = explode(':', $line);
                $blocks[$blockName][$key] = trim($value);
            }
        }

        return $blocks;
    }
}
