<?php

namespace PHPWorldWide\Stats\Tests\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use PHPWorldWide\Stats\Command\GenerateCommand;
use PHPUnit\Framework\TestCase;

class GenerateCommandTest extends TestCase
{
    public function testExecute()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        $application = new Application();
        $application->add(new GenerateCommand());
        $command = $application->find('generate');

        $commandTester = new CommandTester($command);

        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("\n"));

        $exitCode = $commandTester->execute([
            'command' => $command->getName(),
            '--from' => date('Y-m-d', strtotime('last monday')),
            '--to' => date('Y-m-d', strtotime('last monday +7 days'))
        ]);

        $this->assertRegexp('/Exiting/', $commandTester->getDisplay());

        $this->assertSame(0, $exitCode);
    }

    protected function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);

        return $stream;
    }
}
