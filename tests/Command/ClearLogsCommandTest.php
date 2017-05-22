<?php

namespace PhpEarth\Stats\Tests\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use PhpEarth\Stats\Command\ClearLogsCommand;
use PHPUnit\Framework\TestCase;

class ClearLogsCommandTest extends TestCase
{
    public function testExecute()
    {
        $application = new Application();
        $application->add(new ClearLogsCommand());
        $command = $application->find('clear-logs');

        $commandTester = new CommandTester($command);

        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("n\n"));

        $exitCode = $commandTester->execute([
            'command' => $command->getName(),
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
