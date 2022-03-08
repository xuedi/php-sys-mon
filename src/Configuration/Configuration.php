<?php

namespace Xuedi\PhpSysMon\Configuration;

use Symfony\Component\Yaml\Yaml;
use Xuedi\PhpSysMon\FilesystemType;
use Xuedi\PhpSysMon\LinuxPath;
use Xuedi\PhpSysMon\Sensor;
use Xuedi\PhpSysMon\Storage;
use Xuedi\PhpSysMon\StorageCollection;

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

    public function loadStorage(): StorageCollection
    {
        $collection = new StorageCollection();
        foreach ($this->appData['storage'] as $name => $parameters) {
            $collection->add(Storage::fromParameters(
                $name,
                LinuxPath::fromString($parameters['mount']),
                $parameters['partition'],
                FilesystemType::fromString($parameters['fsType']),
                $parameters['disks']
            ));
        }
        return $collection;
    }

    public function loadSensors(): array
    {
        $sensorNodes = [];
        foreach ($this->appData['sensors'] as $provider) {
            $providerName = $provider['provider'];
            $providerItems = $provider['items'];
            foreach ($providerItems as $itemName => $itemProperties) {
                $sensorData = ['value' => [], 'extra' => []];
                foreach ($itemProperties as $propertyType => $propertyData) {
                    $sensorData[$propertyType] = $this->getPropertyValue($propertyData);
                }
                $sensorNodes[] = [
                    'name' => $itemName,
                    'value' => $this->getSensors($providerName, $sensorData['value']),
                    'extra' => $this->getSensors($providerName, $sensorData['extra']),
                ];
            }
        }

        return $sensorNodes;
    }

    private function getSensors($name, array $data): array
    {
        $sensors = [];
        foreach ($data as $value) {
            $sensors[] = Sensor::fromParameters($name, $value);
        }

        return $sensors;
    }

    private function getPropertyValue($propertyData): array
    {
        $sensors = [];
        if (is_array($propertyData)) {
            foreach ($propertyData as $propertyValue) {
                $sensors[] = $propertyValue;
            }
        } else {
            $sensors[] = $propertyData;
        }

        return $sensors;
    }
}
