<?php

declare(strict_types=1);

namespace App\ChainCommandBundle\Command\TestCommands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HeadCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('test_head_command:command')
            ->setDescription('Test Head Command')
        ;
    }

    /**
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Hello from Test Head Command!');

        return Command::SUCCESS;
    }
}