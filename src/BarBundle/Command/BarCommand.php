<?php

declare(strict_types=1);

namespace App\BarBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BarCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('bar:command')
            ->setDescription('Bar Command')
        ;
    }

    /**
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Hello from Bar!');

        return Command::SUCCESS;
    }
}
