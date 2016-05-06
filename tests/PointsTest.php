<?php

namespace PHPWorldWide\Stats\Test;

use PHPWorldWide\Stats\Config;
use PHPWorldWide\Stats\Points;
use PHPWorldWide\Stats\Model\Topic;
use PHPWorldWide\Stats\Model\Comment;
use PHPWorldWide\Stats\Model\Reply;

class PointsTest extends \PHPUnit_Framework_TestCase
{
    private $config;

    protected function setUp()
    {
        $this->config = new Config(__DIR__.'/../app/config/parameters.yml');
    }

    protected static function getMethod($name)
    {
        $class = new \ReflectionClass('PHPWorldWide\Stats\Points');
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }

    public function testAddPointsForTopic()
    {
        $points = new Points($this->config);
        $topic = new Topic();
        $topic->setId(1);
        $topic->setMessage('Lorem ipsum dolor sit amet.');
        $topic->setLikesCount(0);

        $points->addPointsForTopic($topic);

        $this->assertEquals(1, $points->getPointsCount());
    }

    public function testAddPointsForComment()
    {
        $points = new Points($this->config);
        $comment = new Comment();
        $comment->setId(1);
        $comment->setMessage('Lorem ipsum dolor sit amet.');
        $comment->setLikesCount(0);

        $points->addPointsForComment($comment);
        $this->assertEquals(1, $points->getPointsCount());

        $comment_2 = new Comment();
        $comment_2->setId(2);
        $comment_2->setMessage('Lorem ipsum dolor sit amet.');
        $comment_2->setLikesCount(0);
        $points->addPointsForComment($comment_2);
        $this->assertEquals(2, $points->getPointsCount());
    }

    public function testAddPointsForReply()
    {
        $points = new Points($this->config);
        $reply = new Reply();
        $reply->setId(1);
        $reply->setMessage('Lorem ipsum dolor sit amet.');
        $reply->setLikesCount(0);

        $points->addPointsForReply($reply);

        $this->assertEquals(1, $points->getPointsCount());
    }

    public function testAddPointsForLinks()
    {
        $method = self::getMethod('addPointsForLinks');
        $points = new Points($this->config);
        $message = 'http://wwphp-fb.github.io';
        $method->invoke($points, $message);

        $this->assertEquals(20, $points->getPointsCount());
    }
}