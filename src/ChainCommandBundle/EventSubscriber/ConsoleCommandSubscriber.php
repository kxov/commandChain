<?php

declare(strict_types=1);

namespace App\ChainCommandBundle\EventSubscriber;

use App\ChainCommandBundle\Service\ChainCommandService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class provide subscriber for console command before event
 */
final class ConsoleCommandSubscriber implements EventSubscriberInterface
{
    private ChainCommandService $chainCommandService;

    private LoggerInterface $logger;

    public function __construct(
        ChainCommandService $chainCommandService,
        LoggerInterface $logger
    )
    {
        $this->chainCommandService = $chainCommandService;
        $this->logger = $logger;
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleEvents::COMMAND => 'onConsoleCommand',
        ];
    }

    /**
     * Before exec console command check for chain
     *
     * @param ConsoleCommandEvent $event
     * @return void
     * @throws ExceptionInterface
     */
    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        $command = $event->getCommand();
        $commandName = $event->getCommand()->getName();
        $output = $event->getOutput();
        $input = $event->getInput();

        if (!$this->chainCommandService->isChainCommand($commandName)) {
            return;
        }

        if (!$this->chainCommandService->isHead($commandName)) {
            $output->writeln(sprintf(
                "Error: %s command is a member of %s command chain and cannot be executed on its own.",
                $commandName,
                $this->chainCommandService->getHeadName($commandName)
            ));
            $event->disableCommand();
            return;
        }

        $this->logger->info(sprintf(
            '%s is a master command of a command chain that has registered member commands',
            $commandName
        ));

        $childrenCommands = $this->chainCommandService->getChildren($commandName);
        //logging children
        foreach ($childrenCommands as $child) {
            $this->logger->info(sprintf(
                '%s registered as a member of %s command chain',
                $child['name'],
                $commandName
            ));
        }

        $this->logger->info(sprintf(
            'Executing %s command itself first:',
            $commandName
        ));
        $event->disableCommand();
        $command->run($input, $output);

        if (!empty($childrenCommands)) {
            $this->logger->info(sprintf(
                'Executing %s chain members:',
                $commandName
            ));
            $application = $command->getApplication();

            //Executing main chain members
            foreach ($childrenCommands as $child) {
                try {
                    $this->logger->info(sprintf('Executing %s command:', $child['name']));
                    $childCommand = $application->get($child['name']);
                    $childCommand->run($input, $output);
                } catch (\Exception $exception) {
                    $this->logger->error($exception->getMessage());
                    throw $exception;
                }
            }
        }
        $this->logger->info(sprintf(
            'Execution of %s chain completed.',
            $commandName
        ));
    }
}
