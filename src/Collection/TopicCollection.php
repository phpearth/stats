<?php

namespace PHPWorldWide\Stats\Collection;

use PHPWorldWide\Stats\Collection;
use PHPWorldWide\Stats\Model\Topic;

/**
 * Class TopicCollection.
 */
class TopicCollection extends Collection
{
    /**
     * @var int
     */
    private $groupId;

    /**
     * Set group id.
     *
     * @param $groupId
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
    }

    /**
     * Get group id.
     *
     * @return mixed
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * @return int
     */
    public function getNewTopicsCount()
    {
        $count = 0;

        foreach ($this->data as $topic) {
            if ($topic->getCreatedTime() >= $this->startDate && $topic->getCreatedTime() <= $this->endDate) {
                ++$count;
            }
        }

        return $count;
    }

    /**
     * Get most active topic.
     *
     * @return null|Topic
     */
    public function getMostActiveTopic()
    {
        $commentsTopCount = 0;
        $mostActiveTopic = null;

        foreach ($this->data as $topic) {
            if ($topic->getCreatedTime() >= $this->getStartDate() && $topic->getCreatedTime() <= $this->getEndDate()) {
                if ($topic->getCommentsCount() > $commentsTopCount) {
                    $commentsTopCount = $topic->getCommentsCount();
                    $mostActiveTopic = $topic;
                }
            }
        }

        return $mostActiveTopic;
    }

    /**
     * Get most liked topic.
     *
     * return null|Topic
     */
    public function getMostLikedTopic()
    {
        $likesTopCount = 0;
        $mostLikedTopic = null;

        foreach ($this->data as $topic) {
            if ($topic->getCreatedTime() >= $this->getStartDate() && $topic->getCreatedTime() <= $this->getEndDate()) {
                if ($topic->getLikesCount() > $likesTopCount) {
                    $likesTopCount = $topic->getLikesCount();
                    $mostLikedTopic = $topic;
                }
            }
        }

        return $mostLikedTopic;
    }

    /**
     * Fill collection with topics from API data feed array.
     *
     * @param $feed
     *
     * @throws \Exception
     */
    public function addTopicsFromFeed($feed)
    {
        foreach ($feed as $topic) {
            $newTopic = new Topic();
            $newTopic->setId($topic['id']);
            $newTopic->setCreatedTime($topic['created_time']);
            $commentsCount = $topic['commentsCount'];
            if (isset($topic['comments'])) {
                foreach ($topic['comments'] as $comment) {
                    if (isset($comment['comment_count'])) {
                        $commentsCount += $comment['comment_count'];
                    }
                }
            }
            $newTopic->setCommentsCount($commentsCount);
            $newTopic->setLikesCount($topic['likesCount']);

            $this->add($newTopic, $newTopic->getId());
        }
    }
}
