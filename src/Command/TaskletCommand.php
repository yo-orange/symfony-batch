<?php

namespace App\Command;

use App\Service\BatchExecutionService;
use App\Service\Tasklet\ProcessorInterface;
use App\Service\Tasklet\ReaderInterface;
use App\Service\Tasklet\WriterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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

    private ?EntityManagerInterface $entityManager = null;

    private ?EntityManagerInterface $batchEntityManager = null;

    private ?BatchExecutionService $batchExecutionService = null;

    public function __construct(
        LoggerInterface $logger,
        ReaderInterface $reader,
        ProcessorInterface $processor,
        WriterInterface $writer,
        ?EntityManagerInterface $entityManager = null
    ) {
        parent::__construct();

        $this->logger = $logger;
        $this->reader = $reader;
        $this->processor = $processor;
        $this->writer = $writer;
        $this->entityManager = $entityManager;
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
        if ($this->isMultiplePrevention()) {
            $this->batchEntityManager->beginTransaction();
            $this->batchExecutionService->start(static::$defaultName);
        }

        try {
            if ($this->entityManager !== null) {
                $this->entityManager->beginTransaction();
            }

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

            if ($this->entityManager !== null) {
                $this->entityManager->commit();
            }
        } catch (Exception $e) {
            if ($this->entityManager !== null) {
                $this->entityManager->rollback();
            }
            throw $e;
        } finally {
            if ($this->isMultiplePrevention()) {
                $this->batchExecutionService->finish(static::$defaultName);
                $this->batchEntityManager->commit();
            }
        }

        return Command::SUCCESS;
    }

    /**
     * @required
     */
    public function setBatchExecutionService(BatchExecutionService $batchExecutionService): void
    {
        $this->batchExecutionService = $batchExecutionService;
    }

    /**
     * @required
     */
    public function setBatchEntityManager(EntityManagerInterface $batchEntityManager): void
    {
        $this->batchEntityManager = $batchEntityManager;
    }

    /**
     * 多重起動防止判定.
     *
     * @return boolean
     */
    public function isMultiplePrevention() {
        return false;
    }

}
