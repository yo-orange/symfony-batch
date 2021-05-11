<?php

namespace App\Command;

use App\Command\TaskletCommand;
use App\Service\ImportProduct\ImportProductReader;
use App\Service\ImportProduct\ImportProductWriter;
use App\Service\Tasklet\NoOpProcessor;
use Psr\Log\LoggerInterface;

class ImportProductCommand extends TaskletCommand
{
    protected static $defaultName = 'import:product';
    protected static $defaultDescription = 'import product data.';

    public function __construct(
        ImportProductReader $reader,
        NoOpProcessor $processor,
        ImportProductWriter $writer,
        LoggerInterface $eventlogger
    ) {
        parent::__construct($eventlogger, $reader, $processor, $writer, null, null);
    }

    protected function configure(): void
    {
        $this->setDescription(self::$defaultDescription);
    }
}
