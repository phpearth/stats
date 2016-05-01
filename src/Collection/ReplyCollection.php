<?php

namespace PHPWorldWide\Stats\Collection;

use PHPWorldWide\Stats\Collection;
use PHPWorldWide\Stats\Model\Reply;

class ReplyCollection extends Collection
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

    public function addRepliesFromFeed($feed)
    {
        foreach ($feed as $topic) {
            if (isset($topic['comments'])) {
                foreach ($topic['comments'] as $comment) {
                    if (isset($comment['comments'])) {
                        foreach ($comment['comments'] as $reply) {
                            if ($reply['created_time'] >= $this->startDate && $reply['created_time'] <= $this->endDate && isset($reply['from'])) {
                                $replyModel = new Reply();
                                $replyModel->setId($reply['id']);
                                $this->add($replyModel, $replyModel->getId());
                            }
                        }
                    }
                }
            }
        }
    }
}
