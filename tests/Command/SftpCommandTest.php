<?php

namespace App\Tests\Command;

use App\Command\SftpCommand;
use App\Tests\TestUtils;

class SftpCommandTest extends AbstractCommandTest
{
    /**
     * execute command.
     *
     * @return void
     * @test
     */
    public function successExecuteCommand(): void
    {
        $this->executeCommand(SftpCommand::class, []);

        static::assertEquals(
            [
                '/',
                '/app/var/log/product.csv -> /upload/product.csv',
                'put success. /upload/product.csv',
                '/home/foo',
                '/app/var/log/product.csv -> /home/foo/upload/product.csv',
                'put success. /home/foo/upload/product.csv',
            ],
            TestUtils::getLoggerMessages(self::$container)
        );
    }
}
