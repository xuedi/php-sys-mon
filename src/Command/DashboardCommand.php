<?php

namespace Xuedi\PhpSysMon\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Xuedi\PhpSysMon\Service\StorageService;

class DashboardCommand extends Command
{
    protected static $defaultName = 'dashboard';
    private StorageService $storageService;

    public function __construct(StorageService $storageService)
    {
        parent::__construct();
        $this->storageService = $storageService;
    }

    protected function configure(): void
    {
        $this->setHelp('Shows systems dashboard');
        $this->setDescription('Show the dashboard');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('DashboardCommand::execute');

        $table = new Table($output);
        $table->setHeaders($this->storageService->getHeaders());
        $table->setRows($this->storageService->getRows());
        $table->render();

        return Command::SUCCESS;
    }
}