<?php

namespace PhpEarth\Stats\Tests;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use PhpEarth\Stats\Log;
use PHPUnit\Framework\TestCase;

class LogTest extends TestCase
{
    private $log;

    private $root;

    public function setUp()
    {
        vfsStreamWrapper::register();
        $this->root = vfsStream::setup('logroot');
        $this->log = new Log([
            'log_dir' => vfsStream::url('logroot'),
        ]);
    }

    public function testLogTopic()
    {
        for ($i = 0; $i < 500; $i++) {
            $this->log->logTopic('2204685680_0000481525895681	 Reactions: 83	 Comments: 3');
        }
        $this->assertTrue($this->root->hasChild($this->log->getTimestamp().'/topics.log'));
    }

    public function testLogContributor()
    {
        for ($i = 0; $i < 2000; $i++) {
            $this->log->logContributor($i.'    john doe  100');
        }
        $this->assertTrue($this->root->hasChild($this->log->getTimestamp().'/contributors.log'));
    }

    public function testLogNewUser()
    {
        for ($i = 0; $i < 800; $i++) {
            $this->log->logNewUser($i.'	John Doe');
        }
        $this->assertTrue($this->root->hasChild($this->log->getTimestamp().'/newUsers.log'));
    }

    public function testClearLogs()
    {
        $structure = [
            '.gitkeep' => '',
            '20160516000000' => [],
            '20160516000001' => [
                'topics.log' => ''
            ],
        ];

        vfsStream::create($structure);

        $this->assertFileExists(vfsStream::url('logroot/.gitkeep'));
        $this->assertTrue($this->root->hasChild('20160516000000'));
        $this->assertFileExists(vfsStream::url('logroot/20160516000001/topics.log'));

        $this->log->clearLogs();

        $this->assertFileExists(vfsStream::url('logroot/.gitkeep'));
        $this->assertFalse($this->root->hasChild('20160516000000'));
        $this->assertFalse($this->root->hasChild('20160516000001'));
    }
}
