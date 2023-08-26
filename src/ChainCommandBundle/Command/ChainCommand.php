<?php

declare(strict_types=1);

namespace App\ChainCommandBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChainCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('chain:command')
            ->setDescription('Chain Command')
        ;
    }

    /**
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Hello from Chain!');

        return Command::SUCCESS;
    }
}
