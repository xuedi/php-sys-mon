<?php

namespace Xuedi\PhpSysMon\Service;

use Xuedi\PhpSysMon\Configuration\Configuration;
use Xuedi\PhpSysMon\Storage;

class StorageService
{
    /** @var array<int|Storage> */
    private array $storageList;
    private TemperatureService $tempService;

    public function __construct(Configuration $config, TemperatureService $tempService)
    {
        $this->storageList = $config->loadStorage();
        $this->tempService = $tempService;
    }

    public function getHeaders(): array
    {
        return [
            'Name',
            'Mount',
            'FsType',
            'Size',
            'Used',
            'Temp',
            'Devices',
        ];
    }

    public function getRows(): array
    {
        $displayData = [];
        foreach ($this->storageList as $storage) {
            $displayData[] = [
                $storage->getName(),
                $storage->getMount(),
                $storage->getFsType()->asString(),
                $this->getSize($storage->getMount()),
                $this->getUsed($storage->getMount()),
                $this->getAverageTemperature($storage->getHardDrives()),
                $storage->getHardDriveList(),
            ];
        }

        return $displayData;
    }

    private function getUsed(string $mount): string
    {
        if (!is_dir($mount)) {
            return 0;
        }
        $total = disk_total_space($mount);
        if ($total == 0) {
            return '    -          ';
        }

        $free = disk_free_space($mount);
        $used = disk_total_space($mount) - $free;
        $per = ($used / $total) * 100;

        return round($per) . '% - ' . $this->humanSize($total - $free);
    }

    private function getSize(string $mount): string
    {
        return (is_dir($mount)) ? $this->humanSize(disk_total_space($mount)) : 0;
    }

    private function humanSize($bytes): string
    {
        if ($bytes == 0) {
            return '        -';
        }
        $scale = ["XX", "KB", "MB", "GB", "TB", "PB"];
        $i = 0;
        while ($bytes >= 1000) {
            $bytes /= 1000;
            $i++;
        }
        return sprintf("%6.2f", $bytes) . " " . $scale[$i];
    }

    private function getAverageTemperature(array $drives): string
    {
        $temperatures = [];
        foreach ($drives as $drive) {
            $temperatures[] = $this->tempService->getHardDrive($drive);
        }

        return round(array_sum($temperatures) / count($temperatures)) . '°';
    }
}
