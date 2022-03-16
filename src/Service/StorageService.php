<?php

namespace Xuedi\PhpSysMon\Service;

use Xuedi\PhpSysMon\Configuration\Configuration;
use Xuedi\PhpSysMon\Helpers\FilesystemWrapper;
use Xuedi\PhpSysMon\LinuxPath;
use Xuedi\PhpSysMon\StorageCollection;

class StorageService
{
    private array $cache = [];
    private StorageCollection $storageList;
    private TemperatureService $tempService;
    private FilesystemWrapper $fsWrapper;

    public function __construct(Configuration $config, TemperatureService $tempService, FilesystemWrapper $fsWrapper)
    {
        $this->storageList = $config->loadStorage();
        $this->tempService = $tempService;
        $this->fsWrapper = $fsWrapper;
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
                $storage->getMount()->asString(),
                $storage->getFsType()->asString(),
                $this->getSize($storage->getMount()),
                $this->getUsed($storage->getMount()),
                $this->getAverageTemperature($storage->getHardDrives()),
                $this->getHardDriveList($storage->getHardDrives()),
            ];
        }

        return $displayData;
    }

    private function getUsed(LinuxPath $mount): string
    {
        if (!$this->fsWrapper->is_dir($mount->asString())) {
            return '0';
        }
        $total = $this->getTotalDiskSpace($mount->asString());
        if ($total == 0) {
            return '    -          ';
        }

        $free = $this->fsWrapper->disk_free_space($mount->asString());
        $used = $this->getTotalDiskSpace($mount->asString()) - $free;
        $per = ($used / $total) * 100;

        return round($per) . '% - ' . $this->humanSize($total - $free);
    }

    private function getSize(LinuxPath $past): string
    {
        return (is_dir($past->asString())) ? $this->humanSize($this->getTotalDiskSpace($past->asString())) : '0';
    }

    private function humanSize(float $bytes): string
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
            $temperatures[] = $this->tempService->measure($drive);
        }

        return round(array_sum($temperatures) / count($temperatures)) . '°';
    }

    private function getHardDriveList(array $drives): string
    {
        $hardDrives = [];
        foreach ($drives as $hardDrive) {
            $driveName = $hardDrive->getDevice()->asString();
            $driveName = str_replace('/dev/', '', $driveName);
            if (str_ends_with($driveName, 'n1')) {
                $driveName = substr($driveName, 0, -2);
            }
            $temperature = $this->tempService->measure($hardDrive);
            $hardDrives[] = "$driveName: {$temperature}°";
        }

        return implode(', ', $hardDrives);
    }

    private function getTotalDiskSpace(string $path): int
    {
        if (isset($this->cache[$path])) {
            return $this->cache[$path];
        }
        $space = $this->fsWrapper->disk_total_space($path);
        $this->cache[$path] = $space;

        return (int)$space;
    }
}
