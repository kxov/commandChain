<?php

declare(strict_types=1);

namespace App\ChainCommandBundle\Tests\Functional;

use App\ChainCommandBundle\Command\TestCommands\ChildCommand;
use App\ChainCommandBundle\Command\TestCommands\HeadCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class ChainCommandTest extends KernelTestCase
{
    private Application $app;
    private BufferedOutput $output;

    protected function setUp(): void
    {
        static::bootKernel();
        $this->app = new Application(static::$kernel);
        $this->app->setAutoExit(false);
        $this->output = new BufferedOutput();
    }

    public function testChainSuccess()
    {
        $headCommand = new HeadCommand();
        $childCommand = new ChildCommand();

        $this->app->add($headCommand);
        $this->app->add($childCommand);

        $container = static::getContainer();
        $service = $container->get('chain.command.service');

        $service->addCommand($childCommand, $headCommand->getName());
        $this->app->run(new ArrayInput([$headCommand->getName()]), $this->output);

        self::assertEquals("Hello from Test Head Command!\nHello from Test Command!\n", $this->output->fetch());
    }

    public function testChainFailed()
    {
        $headCommand = new HeadCommand();
        $childCommand = new ChildCommand();

        $this->app->add($headCommand);
        $this->app->add($childCommand);

        $container = static::getContainer();
        $service = $container->get('chain.command.service');

        $service->addCommand($childCommand, $headCommand->getName());
        $this->app->run(new ArrayInput([$childCommand->getName()]), $this->output);

        self::assertEquals(
            "Error: test_child_command:command command is a member of test_head_command:command command chain and cannot be executed on its own.\n",
            $this->output->fetch());
    }
}