<?php

namespace PHPWorldWide\Stats;

use PHPWorldWide\Stats\Collection\TopicCollection;
use PHPWorldWide\Stats\Collection\UserCollection;
use PHPWorldWide\Stats\Mapper;
use Symfony\Component\Console\Helper\ProgressBar;

class Service
{
    private $config;

    private $progress;

    public function __construct(Config $config, ProgressBar $progress)
    {
        $this->config = $config;
        $this->progress = $progress;
        $this->mapper = new Mapper($this->config, $this->progress);
    }

    public function getTopics($startDate, $endDate)
    {
        $topics = new TopicCollection();
        $this->mapper->mapTopics($topics, $startDate, $endDate);

        return $topics;
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