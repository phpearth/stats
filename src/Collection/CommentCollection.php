<?php

namespace PHPWorldWide\Stats\Collection;

use PHPWorldWide\Stats\Collection;
use PHPWorldWide\Stats\Model\Comment;

/**
 * Class CommentCollection.
 */
class CommentCollection extends Collection
{
    /**
     * Add comments from fetched API data feed.
     *
     * @param array $feed
     *
     * @throws \Exception
     */
    public function addCommentsFromFeed($feed)
    {
        foreach ($feed as $topic) {
            if (isset($topic['comments'])) {
                foreach ($topic['comments'] as $comment) {
                    if ($comment['created_time'] >= $this->startDate && $comment['created_time'] <= $this->endDate && isset($comment['from'])) {
                        $commentModel = new Comment();
                        $commentModel->setId($comment['id']);
                        $commentModel->setMessage($comment['message']);
                        $this->add($commentModel, $commentModel->getId());
                    }
                }
            }
        }
    }
}
