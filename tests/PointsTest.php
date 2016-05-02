<?php

namespace PHPWorldWide\Stats\Test;

use PHPWorldWide\Stats\Points;
use PHPWorldWide\Stats\Model\Topic;
use PHPWorldWide\Stats\Model\Comment;
use PHPWorldWide\Stats\Model\Reply;

class PointsTest extends \PHPUnit_Framework_TestCase
{
    public function testAddPointsForTopic()
    {
        $points = new Points();
        $topic = new Topic();
        $topic->setId(1);
        $topic->setMessage('Lorem ipsum dolor sit amet.');
        $topic->setLikesCount(0);

        $points->addPointsForTopic($topic);

        $this->assertEquals(1, $points->getPointsCount());

        $comment = new Comment();
        $comment->setId(1);
        $comment->setMessage('Lorem ipsum dolor sit amet.');
        $comment->setLikesCount(0);

        $points->addPointsForComment($comment);

        $this->assertEquals(2, $points->getPointsCount());

        $reply = new Reply();
        $reply->setId(1);
        $reply->setMessage('Lorem ipsum dolor sit amet.');
        $reply->setLikesCount(0);

        $points->addPointsForReply($reply);

        $this->assertEquals(3, $points->getPointsCount());
    }
}