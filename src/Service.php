<?php

namespace PHPWorldWide\Stats;

use PHPWorldWide\Stats\Collection\TopicCollection;
use PHPWorldWide\Stats\Collection\CommentCollection;
use PHPWorldWide\Stats\Collection\ReplyCollection;
use PHPWorldWide\Stats\Collection\UserCollection;
use PHPWorldWide\Stats\Mapper as BaseMapper;
use Symfony\Component\Console\Helper\ProgressBar;

class Service
{
    private $config;

    private $progress;

    public function __construct(Config $config, ProgressBar $progress)
    {
        $this->config = $config;
        $this->progress = $progress;
        $this->mapper = new BaseMapper($this->config, $this->progress);
    }

    public function getTopics($startDate, $endDate)
    {
        $topics = new TopicCollection();
        $this->mapper->mapTopics($topics, $startDate, $endDate);

        return $topics;
    }

    public function getComments($startDate, $endDate)
    {
        $comments = new CommentCollection();
        $this->mapper->mapComments($comments, $startDate, $endDate);

        return $comments;
    }

    public function getReplies($startDate, $endDate)
    {
        $replies = new ReplyCollection();
        $this->mapper->mapReplies($replies, $startDate, $endDate);

        return $replies;
    }

    public function getUsers($startDate, $endDate)
    {
        $users = new UserCollection();
        $this->mapper->mapUsers($users, $startDate, $endDate);

        return $users;
    }

    public function getNewUsersCount()
    {
        return $this->mapper->getNewUsersCount();
    }
}
