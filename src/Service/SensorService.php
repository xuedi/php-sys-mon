<?php

namespace Xuedi\PhpSysMon\Service;

use Xuedi\PhpSysMon\Configuration\Configuration;

class SensorService
{
    private array $sensorNodes;
    private LmSensorsService $lmSensors;

    public function __construct(Configuration $config, LmSensorsService $lmSensors)
    {
        $this->sensorNodes = $config->loadSensors();
        $this->lmSensors = $lmSensors;
    }

    public function getRows(): array
    {
        $rows = [];
        foreach ($this->sensorNodes as $sensorNode) {
            $key = $sensorNode['name'];
            $temp = $this->getNode($sensorNode['value']);
            $extra = $this->getNode($sensorNode['extra']);
            $rows[] = [$key, $temp, $extra];
        }
        return $rows;
    }

    private function getNode(array $sensorNode): string
    {
        $list = [];
        foreach ($sensorNode as $sensor) {
            $list[] = $this->lmSensors->read($sensor);
        }
        return implode(', ', $list);
    }
}
