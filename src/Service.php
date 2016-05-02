<?php

namespace PHPWorldWide\Stats;

use PHPWorldWide\Stats\Collection\TopicCollection;
use PHPWorldWide\Stats\Collection\CommentCollection;
use PHPWorldWide\Stats\Collection\ReplyCollection;
use PHPWorldWide\Stats\Collection\UserCollection;
use PHPWorldWide\Stats\Mapper as BaseMapper;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 * Class Service.
 */
class Service
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var ProgressBar
     */
    private $progress;

    /**
     * @var Mapper
     */
    private $mapper;

    /**
     * Service constructor.
     *
     * @param Config      $config
     * @param ProgressBar $progress
     */
    public function __construct(Config $config, ProgressBar $progress)
    {
        $this->config = $config;
        $this->progress = $progress;
        $this->mapper = new BaseMapper($this->config, $this->progress);
    }

    /**
     * Get topics collection from API data feed via mapper.
     *
     * @param $startDate
     * @param $endDate
     *
     * @return TopicCollection
     */
    public function getTopics($startDate, $endDate)
    {
        $topics = new TopicCollection();
        $this->mapper->mapTopics($topics, $startDate, $endDate);

        return $topics;
    }

    /**
     * Get comments collection from API data feed via mapper.
     *
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     *
     * @return CommentCollection
     */
    public function getComments($startDate, $endDate)
    {
        $comments = new CommentCollection();
        $this->mapper->mapComments($comments, $startDate, $endDate);

        return $comments;
    }

    /**
     * Get replies collection from API data via mapper.
     *
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     *
     * @return ReplyCollection
     */
    public function getReplies($startDate, $endDate)
    {
        $replies = new ReplyCollection();
        $this->mapper->mapReplies($replies, $startDate, $endDate);

        return $replies;
    }

    /**
     * Get users collection from API data via mapper.
     *
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     *
     * @return UserCollection
     */
    public function getUsers($startDate, $endDate)
    {
        $users = new UserCollection();
        $this->mapper->mapUsers($users, $startDate, $endDate);

        return $users;
    }

    /**
     * Get the number of new users since the user's name configured in app
     * configuration.
     *
     * @return int
     *
     * @throws \Exception
     */
    public function getNewUsersCount()
    {
        return $this->mapper->getNewUsersCount();
    }
}
