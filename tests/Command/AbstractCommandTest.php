<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\ApplicationTester;
use Symfony\Component\Console\Tester\CommandTester;

abstract class AbstractCommandTest extends KernelTestCase
{

    public function getComponent($class)
    {
        if (!self::$booted) {
            self::bootKernel();
        }
        return self::$container->get($class);
    }

    public function executeCommand($class, array $arguments, array $inputs = []): void
    {
        $command = $this->getComponent($class);
        $command->setApplication(new Application(self::$kernel));

        $commandTester = new CommandTester($command);
        $commandTester->setInputs($inputs);
        $commandTester->execute($arguments);
    }

    public function executeApplication($class, array $inputs, array $options = []): void
    {
        $command = $this->getComponent($class);
        $application = new Application(self::$kernel);
        $application->setAutoExit(false);
        $command->setApplication($application);

        $applicationTester = new ApplicationTester($application);

        $applicationTester->run($inputs, $options);
    }
}
