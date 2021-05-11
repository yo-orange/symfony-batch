<?php

namespace App\Command;

use App\Service\BatchExecutionService;
use App\Service\Tasklet\ProcessorInterface;
use App\Service\Tasklet\ReaderInterface;
use App\Service\Tasklet\WriterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class TaskletCommand extends Command
{
    private LoggerInterface $logger;

    private ReaderInterface $reader;

    private ProcessorInterface $processor;

    private WriterInterface $writer;

    private ?EntityManagerInterface $batchEntityManager;

    private ?BatchExecutionService $batchExecutionService;

    public function __construct(
        LoggerInterface $logger,
        ReaderInterface $reader,
        ProcessorInterface $processor,
        WriterInterface $writer,
        ?EntityManagerInterface $batchEntityManager,
        ?BatchExecutionService $batchExecutionService
    ) {
        parent::__construct();

        $this->logger = $logger;
        $this->reader = $reader;
        $this->processor = $processor;
        $this->writer = $writer;
        $this->batchEntityManager = $batchEntityManager;
        $this->batchExecutionService = $batchExecutionService;
    }

    /**
     * {@inheritDoc}
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return integer
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($this->batchEntityManager !== null) {
            $this->batchEntityManager->beginTransaction();
            $this->batchExecutionService->start(static::$defaultName);
        }

        try {
            $count = 0;

            $this->logger->info("start tasklet.");

            $this->reader->open();

            while (($input = $this->reader->read()) != null) {
                $count++;
                $this->logger->info("start process {$count}time.");

                $output = $this->processor->process($input);

                if ($output !== null) {
                    $this->writer->write($output);
                }

                $this->logger->info("end process {$count}time.");
            }

            $this->writer->close();

            $this->logger->info("end tasklet.");
        } finally {
            if ($this->batchEntityManager !== null) {
                $this->batchExecutionService->finish(static::$defaultName);
                $this->batchEntityManager->commit();
            }
        }

        return Command::SUCCESS;
    }
}
