<?php

namespace App\Command;

use App\Command\TaskletCommand;
use App\Service\BatchExecutionService;
use App\Service\ExportProduct\ExportProductReader;
use App\Service\ExportProduct\ExportProductWriter;
use App\Service\Tasklet\NoOpProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ExportProductCommand extends TaskletCommand
{
    protected static $defaultName = 'export:product';
    protected static $defaultDescription = 'export product data.';

    public function __construct(
        ExportProductReader $reader,
        NoOpProcessor $processor,
        ExportProductWriter $writer,
        LoggerInterface $logger,
        EntityManagerInterface $batchEntityManager,
        BatchExecutionService $batchExecutionService
    ) {
        parent::__construct($logger, $reader, $processor, $writer, $batchEntityManager, $batchExecutionService);
    }

    protected function configure(): void
    {
        $this->setDescription(self::$defaultDescription);
    }
}
