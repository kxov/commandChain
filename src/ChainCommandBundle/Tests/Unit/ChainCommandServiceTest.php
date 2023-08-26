<?php

declare(strict_types=1);

namespace App\ChainCommandBundle\Tests\Unit;

use App\BarBundle\Command\BarCommand;
use App\ChainCommandBundle\Service\ChainCommandService;
use App\FooBundle\Command\FooCommand;
use PHPUnit\Framework\TestCase;

class ChainCommandServiceTest extends TestCase
{
    /**
     * @var ChainCommandService
     */
    private ChainCommandService $chainCommandService;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->chainCommandService = new ChainCommandService();
    }

    /**
     *
     * @covers ChainCommandService::addCommand
     * @covers ChainCommandService::getCommands
     * @covers ChainCommandService::isChainCommand
     *
     * @return void
     */
    public function testAddGetIsChainCommandService(): void
    {
        $this->chainCommandService->addCommand(new BarCommand(), 'chain_bar:command', 1);
        $this->chainCommandService->addCommand(new FooCommand(), 'chain_foo:command', 2);

        self::assertCount(2, $this->chainCommandService->getCommands());

        self::assertFalse($this->chainCommandService->isChainCommand('a'));
        self::assertTrue($this->chainCommandService->isChainCommand('chain_bar:command'));
        self::assertTrue($this->chainCommandService->isChainCommand('chain_foo:command'));
        self::assertTrue($this->chainCommandService->isChainCommand('bar:command'));
        self::assertTrue($this->chainCommandService->isChainCommand('foo:command'));
        self::assertFalse($this->chainCommandService->isChainCommand('bar_not_in_chain:command'));
    }

    /**
     *
     * @covers ChainCommandService::isHead
     * @covers ChainCommandService::getHeadName
     *
     * @return void
     */
    public function testIsHeadGetHeadNameChainCommandService(): void
    {
        $this->chainCommandService->addCommand(new BarCommand(), 'chain:command', 1);
        $this->chainCommandService->addCommand(new FooCommand(), 'chain:command', 2);

        self::assertCount(1, $this->chainCommandService->getCommands());

        self::assertTrue($this->chainCommandService->isHead('chain:command'));
        self::assertEquals('chain:command', $this->chainCommandService->getHeadName('bar:command'));
    }

    /**
     *
     * @covers ChainCommandService::getCommands
     *
     * @return void
     */
    public function testPriorityChainCommandService(): void
    {
        $this->chainCommandService->addCommand(new BarCommand(), 'chain:command', 40);
        $this->chainCommandService->addCommand(new FooCommand(), 'chain:command', 11);

        self::assertCount(1, $this->chainCommandService->getCommands());

        self::assertEquals($this->chainCommandService->getChildren('chain:command')[0]['name'], 'foo:command');
    }
}
