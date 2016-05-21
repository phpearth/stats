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
        $config->addFile(__DIR__.'/../app/config/points.yml');
        $config->addFile(__DIR__.'/../app/config/offensive_words.yml');
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
    public function testAddPointsForTopic($message, $likes, $type, $canComment, $expectedPoints)
    {
        $topic = new Topic();
        $topic->setId(1);
        $topic->setMessage($message);
        $topic->setLikesCount($likes);
        $topic->setType($type);
        $topic->setCanComment($canComment);

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

    public function topicsProvider()
    {
        return [
            'topic' => ['Lorem ipsum dolor sit amet.', 0, 'status', true, 1],
            'topic_with_likes' => ['Lorem ipsum dolor sit amet', 5, 'status', true, 2],
            'topic_with_more_likes' => ['Lorem ipsum dolor sit amet', 11, 'status', true, 3],
            'topic_with_a_lot_likes' => ['Lorem ipsum dolor sit amet', 1000, 'status', true, 16],
            'photo_with_description' => ['Lorem ipsum dolor sit amet', 11, 'photo', true, 3],
            'photo_only' => ['', 11, 'photo', true, 0],
            'gif' => ['', 11, 'animated_image_share', true, 0],
            'closed_topic' => ['Lorem ipsum dolor sit amet', 50, 'status', false, 0],
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
            ['http://lmgtfy.com', -10],
            ['http://lmgtfy.com http://wwwphp-fb.github.io', 10],
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
