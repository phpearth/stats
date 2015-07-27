<?php

namespace PHPWorldWide\Stats\Collection;

use PHPWorldWide\Stats\Collection;
use PHPWorldWide\Stats\Model\Topic;

class TopicCollection extends Collection
{
    private $startDate;

    private $endDate;

    private $groupId;

    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
    }

    public function getGroupId()
    {
        return $this->groupId;
    }

    public function getNewTopicsCount()
    {
        $count = 0;

        foreach ($this->data as $topic) {
            if ($topic->getCreatedTime() >= $this->startDate && $topic->getCreatedTime() <= $this->endDate) {
                $count ++;
            }
        }

        return $count;
    }

    public function getMostActiveTopicId()
    {
        $commentsTopCount = 0;
        $topicId = null;

        foreach ($this->data as $topic) {
            if ($topic->getCreatedTime() >= $this->getStartDate() && $topic->getCreatedTime() <= $this->getEndDate()) {
                if ($topic->getCommentsCount() > $commentsTopCount) {
                    $commentsTopCount = $topic->getCommentsCount();
                    $topicId = $topic->getId();
                }
            }
        }

        $topicId = str_replace($this->getGroupId() . '_', '', $topicId);

        return $topicId;
    }

    public function getMostLikedTopicId()
    {
        $likesTopCount = 0;
        $topicId = null;

        foreach ($this->data as $topic) {
            if ($topic->getCreatedTime() >= $this->getStartDate() && $topic->getCreatedTime() <= $this->getEndDate()) {
                if ($topic->getLikesCount() > $likesTopCount) {
                    $likesTopCount = $topic->getLikesCount();
                    $topicId = $topic->getId();
                }
            }
        }

        $topicId = str_replace($this->getGroupId() . '_', '', $topicId);

        return $topicId;
    }

    public function addTopicsFromFeed($feed)
    {
        foreach ($feed as $topic) {
            $newTopic = new Topic();
            $newTopic->setId($topic['id']);
            $newTopic->setCreatedTime($topic['created_time']);
            $newTopic->setCommentsCount($topic['commentsCount']);
            $newTopic->setLikesCount($topic['likesCount']);

            $this->add($newTopic, $newTopic->getId());
        }
    }
}