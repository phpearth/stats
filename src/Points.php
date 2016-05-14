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
     * Points constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Add points based on creating a topic.
     *
     * @param Topic $topic
     *
     * @return int
     */
    public function addPointsForTopic(Topic $topic)
    {
        // Add 1 point for creating a topic
        $points = 1;

        // Add points based on the topic likes
        $points += ($topic->getLikesCount() >= 100) ? 15 : ceil($topic->getLikesCount() / 10);

        // Add points for using recommended links
        $points += $this->addPointsForLinks($topic->getMessage());

        // Remove points for using offensive words
        $points += $this->getOffensivePoints($topic->getMessage());

        return $points;
    }

    /**
     * Add points based on comment.
     *
     * @param Comment $comment
     *
     * @return int
     */
    public function addPointsForComment(Comment $comment)
    {
        $points = 1;
        $points += ($comment->getLikesCount() > 100) ? 11 : ceil($comment->getLikesCount() / 10);
        $points += (strlen($comment->getMessage()) > 100) ? 1 : 0;

        // Add points for using recommended links
        $points += $this->addPointsForLinks($comment->getMessage());

        // Remove points for using offensive words
        $points += $this->getOffensivePoints($comment->getMessage());

        return $points;
    }

    /**
     * Add points based on reply.
     * 
     * @param Reply $reply
     *
     * @return int
     */
    public function addPointsForReply(Reply $reply)
    {
        $points = 1;
        $points += ($reply->getLikesCount() > 100) ? 11 : ceil($reply->getLikesCount() / 10);
        $points += (strlen($reply->getMessage()) > 100) ? 1 : 0;

        // Add points for using recommended links
        $points += $this->addPointsForLinks($reply->getMessage());

        // Remove points for using offensive words
        $points += $this->getOffensivePoints($reply->getMessage());

        return $points;
    }

    /**
     * Add points for attaching recommended links.
     *
     * @param string $message
     *
     * @return int
     */
    private function addPointsForLinks($message)
    {
        $points = 0;
        if (isset($message)) {
            foreach ($this->config->get('urls') as $url) {
                if (false !== stripos($message, $url[0])) {
                    $points = ($points > $url[1]) ? $points : $url[1];
                }
            }
        }

        return $points;
    }

    /**
     * Remove points for using offensive and inappropriate keywords.
     *
     * @param string $message
     *
     * @return int
     */
    private function getOffensivePoints($message)
    {
        $points = 0;
        if (isset($message)) {
            foreach ($this->config->get('offensive_words') as $keyword) {
                if (false !== stripos($message, $keyword[0])) {
                    $points = ($points < $keyword[1]) ? $points : $keyword[1];
                }
            }
        }

        return $points;
    }
}
