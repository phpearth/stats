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
        $this->language = new ExpressionLanguage();
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
        // Add points for creating a topic
        $points = $this->config->get('points_for_topic');

        // Add points based on the topic likes
        $points += $this->language->evaluate($this->config->get('points_for_topic_likes'), ['topic' => $topic]);

        // Add points for using recommended links
        $points += $this->addPointsForLinks($topic->getMessage());

        // Set points for only photo shares
        $points = $this->language->evaluate($this->config->get('points_for_image'), ['topic' => $topic, 'points' => $points]);

        // Set points if topic is closed for comments
        $points = $this->language->evaluate($this->config->get('points_for_closed_topic'), ['topic' => $topic, 'points' => $points]);

        // Remove points for using offensive words
        $points += $this->getOffensivePoints($topic->getMessage());

        // Add points for special admin topics
        $points += $this->language->evaluate($this->config->get('points_for_admin_topics'), ['topic' => $topic, 'admins' => $this->config->get('admins')]);

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
        $points = $this->config->get('points_for_comment');
        $points += $this->getPointsForLikes($comment->getLikesCount());
        $points += $this->getPointsForMessageLength($comment->getMessage());

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
        $points = $this->config->get('points_for_reply');
        $points += $this->getPointsForLikes($reply->getLikesCount());
        $points += $this->getPointsForMessageLength($reply->getMessage());

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
        $positivePoints = 0;
        $negativePoints = 0;

        if (isset($message)) {
            foreach ($this->config->get('urls') as $url) {
                if (false !== stripos($message, $url[0])) {
                    $positivePoints = ($url[1] > 0 && $positivePoints < $url[1]) ? $positivePoints : $url[1];
                    $negativePoints = ($url[1] <= 0 && $negativePoints > $url[1]) ? $negativePoints : $url[1];
                }
            }
        }

        return $positivePoints + $negativePoints;
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

    /**
     * Get points based on message length
     *
     * @param $message
     *
     * @return int
     */
    private function getPointsForMessageLength($message)
    {
        return $this->language->evaluate($this->config->get('points_message_length'), ['message' => $message]);
    }

    /**
     * Get points based on number of likes.
     *
     * @param $likes
     *
     * @return int
     */
    private function getPointsForLikes($likes)
    {
        return $this->language->evaluate($this->config->get('points_for_likes'), ['likes' => $likes]);
    }
}
