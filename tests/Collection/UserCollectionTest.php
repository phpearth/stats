<?php

namespace PHPWorldWide\Stats\Tests;

use PHPWorldWide\Stats\Collection\CommentCollection;
use PHPWorldWide\Stats\Collection\ReplyCollection;
use PHPWorldWide\Stats\Collection\UserCollection;
use PHPWorldWide\Stats\Model\Comment;
use PHPWorldWide\Stats\Model\User;
use PHPWorldWide\Stats\Points;
use PHPWorldWide\Stats\Config;

class UserCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTopUsers()
    {
        $config = new Config(__DIR__.'/../../app/config/parameters.yml.dist');
        $config->addFile(__DIR__.'/../Fixtures/ignoredUsers.yml');
        $points = new Points($config);
        $usersData = [
            ['name' => 'Fifth', 'points' => 5],
            ['name' => 'Third', 'points' => 15],
            ['name' => 'Fourth', 'points' => 10],
            ['name' => 'John Doe', 'points' => 20],
            ['name' => 'Fourth', 'points' => 0],
            ['name' => 'First', 'points' => 25],
        ];
        $users = new UserCollection();
        foreach ($usersData as $userData) {
            $user = new User($points);
            $user->setName($userData['name']);
            $user->setFeedComments(new CommentCollection());
            $user->setFeedReplies(new ReplyCollection());

            $reflector = new \ReflectionProperty(User::class, 'pointsCount');
            $reflector->setAccessible(true);
            $reflector->setValue($user, $userData['points']);
            $users->add($user);
        }

        $this->assertEquals([
            $users->get(5),
            $users->get(3),
            $users->get(1),
            $users->get(2),
            $users->get(0),
            $users->get(4)
        ], $users->getTopUsers());

        $this->assertEquals([
            $users->get(5),
            $users->get(1),
            $users->get(2)
        ], $users->getTopUsers(3, $config->get('ignored_users')));
    }
}
