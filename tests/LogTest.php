<?php

namespace PHPWorldWide\Stats\Test;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use PHPWorldWide\Stats\Log;


class LogTest extends \PHPUnit_Framework_TestCase
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
            $this->log->logTopic('2204685680_0000481525895681	 Likes: 83	 Comments: 3');
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
}