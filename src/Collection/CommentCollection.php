<?php

namespace PHPWorldWide\Stats\Collection;

use PHPWorldWide\Stats\Collection;
use PHPWorldWide\Stats\Model\Comment;

class CommentCollection extends Collection
{
    private $startDate;

    private $endDate;

    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;
    }

    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;
    }

    public function addCommentsFromFeed($feed)
    {
        foreach ($feed as $topic) {
            if (isset($topic['comments'])) {
                foreach ($topic['comments'] as $comment) {
                    if ($comment['created_time'] >= $this->startDate && $comment['created_time'] <= $this->endDate && isset($comment['from'])) {
                        $commentModel = new Comment();
                        $commentModel->setId($comment['id']);
                        $this->add($commentModel, $commentModel->getId());
                    }
                }
            }
        }
    }
}