<?php

namespace App\Command;

use App\Command\TaskletCommand;
use App\Service\ExportProduct\ExportProductReader;
use App\Service\ExportProduct\ExportProductWriter;
use App\Service\Tasklet\NoOpProcessor;
use Psr\Log\LoggerInterface;

class ExportProductCommand extends TaskletCommand
{
    protected static $defaultName = 'export:product';
    protected static $defaultDescription = 'export product data.';

    public function __construct(
        ExportProductReader $reader,
        NoOpProcessor $processor,
        ExportProductWriter $writer,
        LoggerInterface $logger
    ) {
        parent::__construct($logger, $reader, $processor, $writer, null);
    }

    protected function configure(): void
    {
        $this->setDescription(self::$defaultDescription);
    }

    public function isMultiplePrevention()
    {
        return true;
    }
}
