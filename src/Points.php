<?php

namespace PHPWorldWide\Stats;

use PHPWorldWide\Stats\Model\Topic;
use PHPWorldWide\Stats\Model\Comment;
use PHPWorldWide\Stats\Model\Reply;

/**
 * Class Points.
 */
class Points
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var int
     */
    private $pointsCount = 0;

    /**
     * Points constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Get number of points.
     *
     * @return int
     */
    public function getPointsCount()
    {
        return $this->pointsCount;
    }

    /**
     * Add points based on creating a topic.
     *
     * @param Topic $topic
     */
    public function addPointsForTopic(Topic $topic)
    {
        // Add 1 point for creating a topic
        ++$this->pointsCount;

        // Add points based on the topic likes
        $this->pointsCount += ($topic->getLikesCount() >= 100) ? 15 : ceil($topic->getLikesCount() / 10);

        // Add points for using recommended links
        $this->addPointsForLinks($topic->getMessage());
    }

    /**
     * Add points based on comment.
     *
     * @param Comment $comment
     */
    public function addPointsForComment(Comment $comment)
    {
        ++$this->pointsCount;
        $this->pointsCount += ($comment->getLikesCount() > 100) ? 11 : ceil($comment->getLikesCount() / 10);
        $this->pointsCount += (strlen($comment->getMessage()) > 100) ? 1 : 0;

        // Add points for using recommended links
        $this->addPointsForLinks($comment->getMessage());
    }

    /**
     * Add points based on reply.
     * 
     * @param Reply $reply
     */
    public function addPointsForReply(Reply $reply)
    {
        ++$this->pointsCount;
        $this->pointsCount += ($reply->getLikesCount() > 100) ? 11 : ceil($reply->getLikesCount() / 10);
        $this->pointsCount += (strlen($reply->getMessage()) > 100) ? 1 : 0;

        // Add points for using recommended links
        $this->addPointsForLinks($reply->getMessage());
    }

    /**
     * Add points for attaching recommended links.
     *
     * @param string $message
     */
    private function addPointsForLinks($message)
    {
        if (isset($message)) {
            $top = 0;
            foreach ($this->config->get('urls') as $url) {
                if (false !== strpos($message, $url[0])) {
                    $top = ($top > $url[1]) ? $top : $url[1];
                }
            }
            $this->pointsCount += $top;
        }
    }
}
