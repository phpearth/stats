<?php

namespace PHPWorldWide\Stats\Tests\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use PHPWorldWide\Stats\Command\OffensiveWordsCommand;
use PHPUnit\Framework\TestCase;

class OffensiveWordsCommandTest extends TestCase
{
    public function testExecute()
    {
        $application = new Application();
        $application->add(new OffensiveWordsCommand());
        $command = $application->find('offensive-words');
        $commandTester = new CommandTester($command);
        $exitCode = $commandTester->execute(['command' => $command->getName()]);
        $this->assertSame(0, $exitCode);
    }
}
