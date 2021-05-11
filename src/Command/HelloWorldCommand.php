<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class HelloWorldCommand extends Command
{
    protected static $defaultName = 'HelloWorld';
    protected static $defaultDescription = 'echo HelloWorld.';

    private LoggerInterface $logger;

    private EntityManagerInterface $entityManager;

    private EntityManagerInterface $batchEntityManager;

    public function __construct(
        LoggerInterface $logger,
        EntityManagerInterface $entityManager,
        EntityManagerInterface $batchEntityManager
    ) {
        parent::__construct();

        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->batchEntityManager = $batchEntityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        $rsm = new ResultSetMapping();
        $sql = "SELECT id FROM main.product FOR UPDATE NOWAIT";
        $this->entityManager->beginTransaction();

        $this->logger->info("start main select.");
        $result = $this->entityManager->createNativeQuery($sql, $rsm)->getResult();
        $this->logger->info("end main select.");

        $this->batchEntityManager->beginTransaction();
        $this->logger->info("start sub select.");
        $batchResult = $this->batchEntityManager->createNativeQuery($sql, $rsm)->getResult();
        $this->logger->info("end sub select.");
        $this->batchEntityManager->commit();

        $this->entityManager->commit();

        return Command::SUCCESS;
    }
}
