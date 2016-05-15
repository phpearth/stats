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
     * Get most liked topic.
     *
     * return null|Topic
     */
    public function getMostLikedTopic()
    {
        $likesTopCount = 0;
        $mostLikedTopic = null;

        foreach ($this->data as $topic) {
            if ($topic->getLikesCount() > $likesTopCount) {
                $likesTopCount = $topic->getLikesCount();
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
}
