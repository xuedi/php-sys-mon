<?php

namespace Xuedi\PhpSysMon\Configuration;

use Symfony\Component\Yaml\Yaml;
use Xuedi\PhpSysMon\Sensor;
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
            $sensors[] = new Sensor($name, $value);
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
