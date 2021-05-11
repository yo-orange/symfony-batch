<?php

namespace App\Tests;

use Monolog\Handler\TestHandler;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TestUtils
{
    public static function getLoggerMessages(ContainerInterface $container, bool $logging = false): ?array
    {
        foreach ($container->get('logger')->getHandlers() as $handler) {
            if ($handler instanceof TestHandler) {
                $testHandler = $handler;
                break;
            }
        }
        if (!$testHandler) {
            throw new \RuntimeException('Oops, not exist "test" handler in monolog.');
        }

        if ($logging) {
            var_dump($testHandler->getRecords());
        }

        $messages = [];
        foreach ($testHandler->getRecords() as $message) {
            $messages[] = $message["message"];
        }
        return $messages;
    }
}
