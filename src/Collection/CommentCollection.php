<?php

namespace PHPWorldWide\Stats\Collection;

use PHPWorldWide\Stats\Collection;
use PHPWorldWide\Stats\Model\Comment;

/**
 * Class CommentCollection
 * @package PHPWorldWide\Stats\Collection
 */
class CommentCollection extends Collection
{
    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * Set start date of fetched period.
     * 
     * @param \DateTime $startDate
     */
    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * Set end date of fetched period.
     *
     * @param \DateTime $endDate
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * Add comments from fetched API data feed.
     *
     * @param array $feed
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
