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
     * Get most active topic.
     *
     * @return null|Topic
     */
    public function getMostActiveTopic()
    {
        $commentsTopCount = 0;
        $mostActiveTopic = null;

        foreach ($this->data as $topic) {
            if ($topic->getCommentsCount() > $commentsTopCount) {
                $commentsTopCount = $topic->getCommentsCount();
                $mostActiveTopic = $topic;
            }
        }

        return $mostActiveTopic;
    }

    /**
     * Get top topic with most reactions.
     *
     * return null|Topic
     */
    public function getTopTopic()
    {
        $topCount = 0;
        $mostLikedTopic = null;

        foreach ($this->data as $topic) {
            if ($topic->getReactionsCount() > $topCount) {
                $topCount = $topic->getReactionsCount();
                $mostLikedTopic = $topic;
            }
        }

        return $mostLikedTopic;
    }

    /**
     * Get number of closed topics.
     *
     * @return int
     */
    public function getClosedTopicsCount()
    {
        $count = 0;

        foreach ($this->data as $topic) {
            if (!$topic->getCanComment()) {
                ++$count;
            }
        }

        return $count;
    }

    /**
     * Get most shared topic.
     *
     * @return null|Topic
     */
    public function getMostSharedTopic()
    {
        $topCount = 0;
        $mostSharedTopic = null;

        foreach ($this->data as $topic) {
            if ($topic->getSharesCount() > $topCount) {
                $topCount = $topic->getSharesCount();
                $mostSharedTopic = $topic;
            }
        }

        return $mostSharedTopic;
    }
}
