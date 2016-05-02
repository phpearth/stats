<?php

namespace PHPWorldWide\Stats\Model;

use PHPWorldWide\Stats\Points;

/**
 * Class User.
 */
class User
{
    /**
     * @var int User's id
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Points
     */
    private $points;

    /**
     * @var int
     */
    private $topicsCount = 0;

    /**
     * @var int
     */
    private $commentsCount = 0;

    /**
     * @var int
     */
    private $repliesCount = 0;

    /**
     * User constructor.
     *
     * @param int|null $id
     */
    public function __construct($id = null)
    {
        $this->id = $id;
        $this->points = new Points();
    }

    /**
     * Set user's id.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get user's id.
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user's name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get user's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Increase number of topics and add points for creating a topic.
     *
     * @param Topic $topic User's topic
     */
    public function addTopic(Topic $topic)
    {
        ++$this->topicsCount;
        $this->points->addPointsForTopic($topic);
    }

    /**
     * Increase number of comments and add points.
     *
     * @param Comment $comment
     */
    public function addComment(Comment $comment)
    {
        ++$this->commentsCount;
        $this->points->addPointsForComment($comment);
    }

    /**
     * Increase number of comments and add points.
     *
     * @param Reply $reply
     */
    public function addReply(Reply $reply)
    {
        ++$this->repliesCount;
        $this->points->addPointsForReply($reply);
    }

    /**
     * Get number of user's points.
     *
     * @return int
     */
    public function getPoints()
    {
        return $this->points->getPointsCount();
    }

    /**
     * Get number of user's topics.
     *
     * @return int
     */
    public function getTopicsCount()
    {
        return $this->topicsCount;
    }

    /**
     * Get number of user's comments and replies.
     *
     * @return int
     */
    public function getCommentsCount()
    {
        return $this->commentsCount + $this->repliesCount;
    }
}
