<?php

namespace Xuedi\PhpSysMon\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Xuedi\PhpSysMon\Service\SensorService;
use Xuedi\PhpSysMon\Service\StorageService;
use Xuedi\PhpSysMon\Service\TemperatureService;

class DashboardCommand extends Command
{
    protected static $defaultName = 'dashboard';
    private StorageService $storageService;
    private SensorService $sensorService;

    public function __construct(StorageService $storageService, SensorService $sensorService)
    {
        parent::__construct();
        $this->storageService = $storageService;
        $this->sensorService = $sensorService;
    }

    protected function configure(): void
    {
        $this->setHelp('Shows systems dashboard');
        $this->setDescription('Show the dashboard');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('');

        // file system
        $output->writeln("Filesystem");
        $table = new Table($output);
        $table->setHeaders($this->storageService->getHeaders());
        $table->setRows($this->storageService->getRows());
        $table->render();
        $output->writeln('');

        // sensor data
        $output->writeln("Sensors");
        $table = new Table($output);
        $table->setRows($this->sensorService->getRows());
        $table->render();
        $output->writeln('');

        return Command::SUCCESS;
    }
}
