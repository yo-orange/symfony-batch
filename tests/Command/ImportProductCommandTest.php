<?php

namespace App\Tests\Command;

use App\Command\ImportProductCommand;
use App\Entity\Product;
use App\Tests\TestUtils;
use Doctrine\ORM\EntityManagerInterface;

class ImportProductCommandTest extends AbstractCommandTest
{

    /**
     * execute command.
     *
     * @return void
     * @test
     */
    public function successExecuteCommand(): void
    {
        $this->loadData();
        $this->executeCommand(ImportProductCommand::class, []);

        static::assertEquals(
            17,
            count(TestUtils::getLoggerMessages(self::$container))
        );
    }

    private function loadData()
    {
        $em = $this->getComponent(EntityManagerInterface::class);
        $em->createQuery("DELETE FROM App\Entity\Product")->execute();
        $names = [
            "product1",
            "product2",
            "product3",
        ];

        foreach ($names as $name) {
            $record = (new Product())->setName($name);
            $em->persist($record);
        }

        $em->flush();
    }
}
