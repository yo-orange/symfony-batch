<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Event\ConsoleSignalEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;

class LoggingSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    private bool $hasError = false;

    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        if ($this->logger === null) {
            return;
        }
        $this->logger->info(ConsoleEvents::COMMAND);
    }

    public function onConsoleTerminate(ConsoleTerminateEvent $event)
    {
        if ($this->logger === null) {
            return;
        }
        $this->logger->info(ConsoleEvents::TERMINATE . ":" . $this->hasError);
    }

    public function onConsoleSignal(ConsoleSignalEvent $event)
    {
        if ($this->logger === null) {
            return;
        }
        $this->logger->info(ConsoleEvents::SIGNAL);
    }

    public function onConsoleError(ConsoleErrorEvent $event)
    {
        $this->hasError = true;            
        if ($this->logger === null) {
            return;
        }
        $this->logger->info(ConsoleEvents::ERROR);
    }

    public static function getSubscribedEvents()
    {
        return [
            ConsoleEvents::COMMAND => 'onConsoleCommand',
            ConsoleEvents::TERMINATE => 'onConsoleTerminate',
            ConsoleEvents::SIGNAL => 'onConsoleSignal',
            ConsoleEvents::ERROR => 'onConsoleError',
        ];
    }
}
