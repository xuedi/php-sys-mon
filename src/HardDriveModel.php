<?php

namespace Xuedi\PhpSysMon;

use RuntimeException;

class HardDriveModel
{
    const VALID_Models = [
        'Samsung 870 2TB',
        'Samsung 970 500MB',
        'Samsung 980 1TB',
        'Seagate IronWolf 8TB',
    ];
    private string $model;

    public function __construct(string $model)
    {
        if (!in_array($model, self::VALID_Models)) {
            throw new RuntimeException("Unknown HardDriveModel: [$model]");
        }
        $this->model = $model;
    }
}
