<?php

namespace PHPWorldWide\Stats\Tests;

use PHPWorldWide\Stats\Collection\CommentCollection;
use PHPWorldWide\Stats\Collection\ReplyCollection;
use PHPWorldWide\Stats\Config;
use PHPWorldWide\Stats\Model\Comment;
use PHPWorldWide\Stats\Model\User;
use PHPWorldWide\Stats\Points;
use PHPWorldWide\Stats\Util\Merger;
use PHPUnit\Framework\TestCase;

class MergerTest extends TestCase
{
    public function testGetMergedItems()
    {
        $comments = new CommentCollection();
        $replies = new ReplyCollection();
        $userIds = [1,5,3,5,5,5,6,7,8,9,5,5,11,12,13,14,5,5];
        $config = new Config([__DIR__.'/../../app/config/parameters.yml.dist']);
        $points = new Points($config);
        $limit = sizeof($userIds);

        for ($i = 0; $i < $limit; $i++) {
            $user = new User($points);
            $user->setFeedComments($comments);
            $user->setFeedReplies($replies);
            $user->setId($userIds[$i]);

            $comment = new Comment();
            $comment->setId($i);
            $comment->setMessage('Lorem ipsum.');
            $comment->setUser($user);
            $comments->add($comment, $comment->getId());
        }

        $mergedComments = $comments->getMergedCommentsByUserId(5);
        $this->assertEquals(4, sizeof($mergedComments));
        $this->assertEquals('Lorem ipsum.', $mergedComments[1]->getMessage());
        $this->assertEquals('Lorem ipsum.Lorem ipsum.Lorem ipsum.', $mergedComments[5]->getMessage());
        $this->assertEquals('Lorem ipsum.Lorem ipsum.', $mergedComments[11]->getMessage());
        $this->assertEquals('Lorem ipsum.Lorem ipsum.', $mergedComments[17]->getMessage());

        $mergedComments = $comments->getMergedCommentsByUserId(1);
        $this->assertEquals(1, sizeof($mergedComments));
    }
}
