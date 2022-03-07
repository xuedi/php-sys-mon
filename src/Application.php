<?php

namespace Xuedi\PhpSysMon;

use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    const APP_NAME = 'PhpSysMon';

    public function __construct(iterable $commands, string $version)
    {
        foreach ($commands as $command) {
            $this->add($command);
        }

        parent::__construct(self::APP_NAME, $version);
    }
}
