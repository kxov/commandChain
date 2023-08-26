<?php

declare(strict_types=1);

namespace App\BarBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BarNotInChainCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('bar_not_in_chain:command')
            ->setDescription('Bar Command not in chain')
        ;
    }

    /**
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Hello from Bar not in Chain!');

        return Command::SUCCESS;
    }
}
