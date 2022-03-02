<?php

namespace Xuedi\PhpSysMon\Configuration;

use Symfony\Component\Yaml\Yaml;
use Xuedi\PhpSysMon\Storage;

class Configuration
{
    private array $appData;

    public function __construct(string $appPath)
    {
        $this->appData = Yaml::parseFile($appPath);
    }

    public function getHostName(): string
    {
        return $this->appData['hostname'] ?? 'localhost';
    }

    public function loadStorage(): array
    {
        $storage = [];
        foreach ($this->appData['storage'] as $name => $parameters) {
            $storage[] = new Storage($name, $parameters);
        }
        return $storage;
    }
}