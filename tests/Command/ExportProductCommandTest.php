<?php

namespace App\Tests\Command;

use App\Command\ExportProductCommand;
use App\Entity\Product;
use App\Tests\TestUtils;
use Doctrine\ORM\EntityManagerInterface;

class ExportProductCommandTest extends AbstractCommandTest
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
        $this->executeCommand(ExportProductCommand::class, []);

        static::assertEquals(
            [
                'DELETE FROM product',
                '"START TRANSACTION"',
                'INSERT INTO product (name) VALUES (?)',
                'INSERT INTO product (name) VALUES (?)',
                'INSERT INTO product (name) VALUES (?)',
                '"COMMIT"',
                'SELECT p0_.id AS id_0, p0_.name AS name_1 FROM product p0_',
                '"START TRANSACTION"',
                'database :',
                'SELECT command FROM main.batch_job_insance WHERE command = :command FOR UPDATE NOWAIT',
                'UPDATE main.batch_job_insance SET started_at = :started_at WHERE command = :command',
                'start tasklet.',
                'start process 1time.',
                'end process 1time.',
                'start process 2time.',
                'end process 2time.',
                'start process 3time.',
                'end process 3time.',
                'end tasklet.',
                'UPDATE main.batch_job_insance SET finished_at = :finished_at WHERE command = :command',
                '"COMMIT"',
            ],
            TestUtils::getLoggerMessages(self::$container)
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
