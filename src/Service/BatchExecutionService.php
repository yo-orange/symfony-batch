<?php

namespace App\Service;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Psr\Log\LoggerInterface;

class BatchExecutionService
{
    private LoggerInterface $logger;

    private EntityManagerInterface $batchEntityManager;

    public function __construct(
        LoggerInterface $logger,
        EntityManagerInterface $batchEntityManager
    ) {
        $this->batchEntityManager = $batchEntityManager;
        $this->logger = $logger;
    }

    public function start(string $command)
    {
        $rsm = new ResultSetMapping();
        $this->logger->debug("database :{$this->batchEntityManager->getConnection()->getDatabase()}");
        $result = $this->batchEntityManager->createNativeQuery("SELECT command FROM main.batch_job_insance WHERE command = :command FOR UPDATE NOWAIT", $rsm)
            ->setParameter('command', $command)
            ->getResult();
        if ($result !== null) {
            $this->batchEntityManager->createNativeQuery("UPDATE main.batch_job_insance SET started_at = :started_at WHERE command = :command", $rsm)
                ->setParameter('command', $command)
                ->setParameter('started_at', new DateTime())
                ->getResult();
        }
    }

    public function finish(string $command)
    {
        $rsm = new ResultSetMapping();
        $this->batchEntityManager->createNativeQuery("UPDATE main.batch_job_insance SET finished_at = :finished_at WHERE command = :command", $rsm)
            ->setParameter('command', $command)
            ->setParameter('finished_at', new DateTime())
            ->getResult();
    }
}
