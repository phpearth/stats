<?php

namespace PhpEarth\Stats\Tests;

use PhpEarth\Stats\Config;
use PhpEarth\Stats\Points;
use PhpEarth\Stats\Model\Topic;
use PhpEarth\Stats\Model\Comment;
use PhpEarth\Stats\Model\Reply;
use PhpEarth\Stats\Model\User;
use PHPUnit\Framework\TestCase;

class PointsTest extends TestCase
{
    private $points;

    protected function setUp()
    {
        $config = new Config([
            __DIR__.'/../config/parameters.yaml.dist',
            __DIR__.'/../config/points.yaml',
            __DIR__.'/../config/offensive_words.yaml',
            __DIR__.'/Fixtures/parameters.yaml',
        ]);

        $this->points = new Points();
        $this->points->setPoints($config->get('points'));
        $this->points->setAdmins($config->getParameter('admins'));
        $this->points->setOffensiveWords($config->get('offensive_words'));
    }

    protected static function getMethod($name)
    {
        $class = new \ReflectionClass('PhpEarth\Stats\Points');
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * @dataProvider topicsProvider
     */
    public function testAddPointsForTopic($message, $reactions, $type, $canComment, $from, $expectedPoints)
    {
        $topic = new Topic();
        $topic->setId(1);
        $topic->setMessage($message);
        $topic->setReactionsCount($reactions);
        $topic->setType($type);
        $topic->setCanComment($canComment);
        $user = new User($this->points);
        $user->setName($from['name']);
        $user->setId($from['id']);
        $topic->setUser($user);

        $this->assertEquals($expectedPoints, $this->points->addPointsForTopic($topic));
    }

    /**
     * @dataProvider commentsAndRepliesProvider
     */
    public function testAddPointsForComment($message, $reactions, $expectedPoints)
    {
        $comment = new Comment();
        $comment->setId(1);
        $comment->setMessage($message);
        $comment->setReactionsCount($reactions);
        $this->assertEquals($expectedPoints, $this->points->addPointsForComment($comment));
    }

    /**
     * @dataProvider commentsAndRepliesProvider
     */
    public function testAddPointsForReply($message, $reactions, $expectedPoints)
    {
        $reply = new Reply();
        $reply->setId(1);
        $reply->setMessage($message);
        $reply->setReactionsCount($reactions);
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

        $this->assertEquals($expectedPoints, $method->invoke($this->points, str_rot13($message)));
    }

    public function topicsProvider()
    {
        return [
            'topic' => ['Lorem ipsum dolor sit amet.', 0, 'status', true, ['name' => 'User Name', 'id' => 2000], 1],
            'topic_with_reactions' => ['Lorem ipsum dolor sit amet', 5, 'status', true, ['name' => 'User Name', 'id' => 2000], 2],
            'topic_with_more_reactions' => ['Lorem ipsum dolor sit amet', 11, 'status', true, ['name' => 'User Name', 'id' => 2000], 3],
            'topic_with_many_reactions' => ['Lorem ipsum dolor sit amet', 1000, 'status', true, ['name' => 'User Name', 'id' => 2000], 16],
            'photo_with_description' => ['Lorem ipsum dolor sit amet', 11, 'photo', true, ['name' => 'User Name', 'id' => 2000], 3],
            'photo_only' => ['', 11, 'photo', true, ['name' => 'User Name', 'id' => 2000], 0],
            'gif' => ['', 11, 'animated_image_share', true, ['name' => 'User Name', 'id' => 2000], 0],
            'closed_topic' => ['Lorem ipsum dolor sit amet', 50, 'status', false, ['name' => 'User Name', 'id' => 2000], 0],
            'admin_topic' => ['Lorem ipsum dolor sit amet', 0, 'status', true, ['name' => 'Admin User', 'id' => 1000], 6],
        ];
    }

    public function commentsAndRepliesProvider()
    {
        return [
            ['Lorem ipsum dolor sit amet.', 0, 1],
            ['Lorem ipsum dolor sit amet', 5, 2],
            ['Lorem ipsum dolor sit amet', 11, 3],
            [str_repeat("a", 10), 0, 1],
            [str_repeat("a", 50), 0, 2],
            [str_repeat("a", 100), 0, 3],
            [str_repeat("a", 2000), 0, 41],
        ];
    }

    public function linksProvider()
    {
        return [
            ['http://php.earth', 20],
            ['Lorem ipsum dolor stackoverflow.com sit amet.', 10],
            ['http://php.earth and php.net', 20],
            ['http://lmgtfy.com', -10],
            ['http://lmgtfy.com http://php.earth', 10],
        ];
    }

    public function offensiveWordsProvider()
    {
        return [
            ['shpx', -20],
            ["qba'g or ynml", -20],
            ['tb shpx lbhefrys', -20],
            ['Yberz vcfhz qbybe fvg nzrg tb Gb UryY lrf', -20],
        ];
    }
}
