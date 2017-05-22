<?php

namespace PhpEarth\Stats;

use PhpEarth\Stats\Model\Topic;
use PhpEarth\Stats\Model\Comment;
use PhpEarth\Stats\Model\Reply;
use PhpEarth\Stats\Util\CodeDetector;

/**
 * Class Points.
 */
class Points
{
    /**
     * @var ExpressionLanguage
     */
    private $language;

    /**
     * @var CodeDetector
     */
    private $codeDetector;

    /**
     * @var array
     */
    private $points;

    /**
     * @var array
     */
    private $admins;

    /**
     * @var array
     */
    private $offensiveWords;

    /**
     * Points constructor.
     */
    public function __construct()
    {
        $this->language = new ExpressionLanguage();
        $this->codeDetector = new CodeDetector();
    }

    /**
     * Set points configuration values.
     *
     * @param array $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
        $this->codeDetector->setPatterns($points['code_regex']);
        $this->codeDetector->setMinCodeLines($points['min_code_lines']);
    }

    /**
     * Set admins from configuration values.
     *
     * @param array $admins
     */
    public function setAdmins($admins)
    {
        $this->admins = $admins;
    }

    /**
     * Set offensive words.
     *
     * @param array $offensiveWords
     */
    public function setOffensiveWords($offensiveWords)
    {
        $this->offensiveWords = $offensiveWords;
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
        $points = $this->points['topic'];

        // Add points based on the topic reactions
        $points += $this->language->evaluate($this->points['topic_reactions'], ['topic' => $topic]);

        // Add points for using recommended links
        $points += $this->addPointsForLinks($topic->getMessage());

        // Set points for only photo shares
        $points = $this->language->evaluate($this->points['image'], ['topic' => $topic, 'points' => $points]);

        // Set points if topic is closed for comments
        $points = $this->language->evaluate($this->points['closed_topic'], ['topic' => $topic, 'points' => $points]);

        // Set points if message contains code
        $points = $this->language->evaluate($this->points['code'], ['codeDetector' => $this->codeDetector, 'message' => $topic->getMessage(), 'points' => $points]);

        // Remove points for using offensive words
        $points += $this->getOffensivePoints($topic->getMessage());

        // Add points for special admin topics
        $points += $this->language->evaluate($this->points['admin_topic'], ['topic' => $topic, 'admins' => $this->admins]);

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
        $points = $this->points['comment'];
        $points += $this->language->evaluate($this->points['comment_reactions'], ['comment' => $comment]);
        $points += $this->getPointsForMessageLength($comment->getMessage());

        // Add points for using recommended links
        $points += $this->addPointsForLinks($comment->getMessage());

        // Set points if message contains code
        $points = $this->language->evaluate($this->points['code'], ['codeDetector' => $this->codeDetector, 'message' => $comment->getMessage(), 'points' => $points]);

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
        $points = $this->points['reply'];
        $points += $this->language->evaluate($this->points['comment_reactions'], ['comment' => $reply]);
        $points += $this->getPointsForMessageLength($reply->getMessage());

        // Add points for using recommended links
        $points += $this->addPointsForLinks($reply->getMessage());

        // Set points if message contains code
        $points = $this->language->evaluate($this->points['code'], ['codeDetector' => $this->codeDetector, 'message' => $reply->getMessage(), 'points' => $points]);

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
            foreach ($this->points['urls'] as $url) {
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
            foreach ($this->offensiveWords as $keyword) {
                if (false !== stripos($message, str_rot13($keyword[0]))) {
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
        return $this->language->evaluate($this->points['message_length'], ['message' => $message]);
    }
}
