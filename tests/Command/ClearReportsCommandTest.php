<?php

namespace PhpEarth\Stats\Tests\Command;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use PhpEarth\Stats\Command\ClearReportsCommand;
use PHPUnit\Framework\TestCase;

class ClearReportsCommandTest extends TestCase
{
    public function setUp()
    {
        vfsStreamWrapper::register();
        $this->root = vfsStream::setup('reportsroot');
    }

    public function testExecute()
    {
        $application = new Application();
        $application->add(new ClearReportsCommand());
        $command = $application->find('clear-reports');
        $command->setReportsRoot(vfsStream::url('reportsroot/'));

        $commandTester = new CommandTester($command);

        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("n\n"));

        $exitCode = $commandTester->execute([
            'command' => $command->getName(),
        ]);

        $this->assertRegexp('/Exiting/', $commandTester->getDisplay());
        $this->assertSame(0, $exitCode);

        $structure = [
            '.gitkeep' => '',
            '20160516000000' => [],
            '20160516000001' => [
                'topics.txt' => ''
            ],
        ];

        vfsStream::create($structure);

        $this->assertFileExists(vfsStream::url('reportsroot/.gitkeep'));
        $this->assertTrue($this->root->hasChild('20160516000000'));
        $this->assertFileExists(vfsStream::url('reportsroot/20160516000001/topics.txt'));

        $helper->setInputStream($this->getInputStream("y\n"));

        $exitCode = $commandTester->execute([
            'command' => $command->getName(),
        ]);

        $this->assertRegexp('/Reports cleaned./', $commandTester->getDisplay());
        $this->assertSame(0, $exitCode);

        $this->assertFileExists(vfsStream::url('reportsroot/.gitkeep'));
        $this->assertFalse($this->root->hasChild('20160516000000'));
        $this->assertFalse($this->root->hasChild('20160516000001'));
    }

    protected function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);

        return $stream;
    }
}
