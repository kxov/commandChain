<?php

declare(strict_types=1);

namespace App\ChainCommandBundle\Service;

use Symfony\Component\Console\Command\Command;

class ChainCommandService
{
    private array $commands = [];

    public function addCommand(Command $command, string $head): void
    {
        $this->commands[$head][] = [
            'name' => $command->getName(),
        ];
    }

    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * Return true if command in chain
     *
     * @param string $commandName
     * @return bool
     */
    public function isChainCommand(string $commandName): bool
    {
        foreach ($this->commands as $head => $children) {
            if ($commandName === $head) {
                return true;
            }

            foreach ($children as $child) {
                if ($commandName === $child['name']) {
                    return true;
                }
            }
        }

        return false;
    }

    /** Get head name by child
     *
     * @param $name
     * @return bool|string
     */
    public function getHeadName($name): bool|string
    {
        foreach ($this->commands as $head => $children) {
            foreach ($children as $child) {
                if ($name === $child['name']) {
                    return $head;
                }
            }
        }

        return false;
    }

    /**
     * Return children commands by head
     *
     * @param $name
     * @return array
     */
    public function getChildren($name): array
    {
        return $this->commands[$name];
    }

    /**
     * Return true if is head command
     *
     * @param string $commandName
     * @return bool
     */
    public function isHead(string $commandName): bool
    {
        return array_key_exists($commandName, $this->commands);
    }
}
