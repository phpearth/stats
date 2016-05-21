<?php

namespace PHPWorldWide\Stats\Tests;

use PHPWorldWide\Stats\Config;
use PHPWorldWide\Stats\Points;
use PHPWorldWide\Stats\Model\Topic;
use PHPWorldWide\Stats\Model\Comment;
use PHPWorldWide\Stats\Model\Reply;

class PointsTest extends \PHPUnit_Framework_TestCase
{
    private $points;

    protected function setUp()
    {
        $config = new Config(__DIR__.'/../app/config/parameters.yml.dist');
        $this->points = new Points($config);
    }

    protected static function getMethod($name)
    {
        $class = new \ReflectionClass('PHPWorldWide\Stats\Points');
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * @dataProvider topicsProvider
     */
    public function testAddPointsForTopic($message, $likes, $type, $expectedPoints)
    {
        $topic = new Topic();
        $topic->setId(1);
        $topic->setMessage($message);
        $topic->setLikesCount($likes);
        $topic->setType($type);

        $this->assertEquals($expectedPoints, $this->points->addPointsForTopic($topic));
    }

    /**
     * @dataProvider commentsAndRepliesProvider
     */
    public function testAddPointsForComment($message, $likes, $expectedPoints)
    {
        $comment = new Comment();
        $comment->setId(1);
        $comment->setMessage($message);
        $comment->setLikesCount($likes);
        $this->assertEquals($expectedPoints, $this->points->addPointsForComment($comment));
    }

    /**
     * @dataProvider commentsAndRepliesProvider
     */
    public function testAddPointsForReply($message, $likes, $expectedPoints)
    {
        $reply = new Reply();
        $reply->setId(1);
        $reply->setMessage($message);
        $reply->setLikesCount($likes);
        $this->assertEquals($expectedPoints, $this->points->addPointsForReply($reply));
    }

    /**
     * @dataProvider linksProvider
     */
    public function testAddPointsForLinks($message, $expectedPoints)
    {
        $method = self::getMethod('addPointsForLinks');

        $this->assertEquals($expectedPoints, $method->invoke($this->points, $message));
    }

    /**
     * @dataProvider offensiveWordsProvider
     */
    public function testGetOffensivePoints($message, $expectedPoints)
    {
        $method = self::getMethod('getOffensivePoints');

        $this->assertEquals($expectedPoints, $method->invoke($this->points, $message));
    }

    public function testGetClosedTopicPoints()
    {
        $method = self::getMethod('getClosedTopicPoints');

        $openedTopic = new Topic();
        $closedTopic = new Topic();
        $closedTopic->setCanComment(false);

        $this->assertEquals(0, $method->invoke($this->points, $openedTopic));
        $this->assertEquals(-20, $method->invoke($this->points, $closedTopic));
    }

    public function topicsProvider()
    {
        return [
            ['Lorem ipsum dolor sit amet.', 0, 'status', 1],
            ['Lorem ipsum dolor sit amet', 5, 'status', 2],
            ['Lorem ipsum dolor sit amet', 11, 'status', 3],
            ['Lorem ipsum dolor sit amet', 11, 'photo', 3],
            ['', 11, 'photo', 0],
            ['', 11, 'animated_image_share', 0],
        ];
    }

    public function commentsAndRepliesProvider()
    {
        return [
            ['Lorem ipsum dolor sit amet.', 0, 1],
            ['Lorem ipsum dolor sit amet', 5, 2],
            ['Lorem ipsum dolor sit amet', 11, 3],
        ];
    }

    public function linksProvider()
    {
        return [
            ['http://wwwphp-fb.github.io', 20],
            ['Lorem ipsum dolor stackoverflow.com sit amet.', 5],
            ['http://wwwphp-fb.github.io and php.net', 20],
        ];
    }

    public function offensiveWordsProvider()
    {
        return [
            ['fuck', -20],
            ["don't be lazy", -20],
            ['go fuck yourself', -20],
            ['Lorem ipsum dolor sit amet go To HelL yes', -20],
        ];
    }
}
