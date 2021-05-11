<?php

namespace App\Tests\Command;

use App\Command\HelloWorldCommand;
use App\Tests\TestUtils;
use Doctrine\DBAL\Exception\DriverException;
use Exception;

class HelloWorldCommandTest extends AbstractCommandTest
{
    /**
     * execute command.
     *
     * @return void
     * @test
     */
    public function successExecuteCommand(): void
    {
        try {
            $this->executeCommand(HelloWorldCommand::class, []);
        } catch(DriverException $e) {
            $message = "An exception occurred while executing 'SELECT id FROM main.product FOR UPDATE NOWAIT':\n\nSQLSTATE[HY000]: General error: 3572 Statement aborted because lock(s) could not be acquired immediately and NOWAIT is set.";
            static::assertEquals($message, $e->getMessage());
        }

        static::assertEquals(
            [
                '"START TRANSACTION"',
                'start main select.',
                'SELECT id FROM main.product FOR UPDATE NOWAIT',
                'end main select.',
                '"START TRANSACTION"',
                'start sub select.',
                'SELECT id FROM main.product FOR UPDATE NOWAIT'
            ],
            TestUtils::getLoggerMessages(self::$container)
        );
    }

    /**
     * execute application.
     *
     * @return void
     * @test
     */
    public function successExecuteApplication(): void
    {
        $this->executeApplication(HelloWorldCommand::class, ["HelloWorld"]);

        static::assertEquals(
            [
                'console.command',
                'Notified event "console.command" to listener "Symfony\Component\HttpKernel\EventListener\DebugHandlersListener::configure".',
                'Notified event "console.command" to listener "App\EventSubscriber\LoggingSubscriber::onConsoleCommand".',
                '"START TRANSACTION"',
                'start main select.',
                'SELECT id FROM main.product FOR UPDATE NOWAIT',
                'end main select.',
                '"START TRANSACTION"',
                'start sub select.',
                'SELECT id FROM main.product FOR UPDATE NOWAIT',
                'console.error',
                "Error thrown while running command \"HelloWorld\". Message: \"An exception occurred while executing 'SELECT id FROM main.product FOR UPDATE NOWAIT':\n\nSQLSTATE[HY000]: General error: 3572 Statement aborted because lock(s) could not be acquired immediately and NOWAIT is set.\"",
                'Notified event "console.error" to listener "App\EventSubscriber\LoggingSubscriber::onConsoleError".',
                'Notified event "console.error" to listener "Symfony\Bundle\FrameworkBundle\EventListener\SuggestMissingPackageSubscriber::onConsoleError".',
                'Notified event "console.error" to listener "Symfony\Component\Console\EventListener\ErrorListener::onConsoleError".',
                'console.terminate:1',
                'Command "HelloWorld" exited with code "1"',
                'Notified event "console.terminate" to listener "App\EventSubscriber\LoggingSubscriber::onConsoleTerminate".',
                'Notified event "console.terminate" to listener "Symfony\Component\Console\EventListener\ErrorListener::onConsoleTerminate".'
            ],
            TestUtils::getLoggerMessages(self::$container, false)
        );
    }
}
