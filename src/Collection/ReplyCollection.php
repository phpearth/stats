<?php

namespace PHPWorldWide\Stats\Collection;

use PHPWorldWide\Stats\Collection;
use PHPWorldWide\Stats\Model\Reply;

/**
 * Class ReplyCollection
 * @package PHPWorldWide\Stats\Collection
 */
class ReplyCollection extends Collection
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
     * Set Start date of fetched period.
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
     * Fill collection from fetched API data feed.
     *
     * @param array $feed Fetched topics
     * @throws \Exception
     */
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
                                $replyModel->setMessage($reply['message']);
                                $this->add($replyModel, $replyModel->getId());
                            }
                        }
                    }
                }
            }
        }
    }
}
