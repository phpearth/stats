<?php

namespace PHPWorldWide\Stats\Model;


class User
{
    private $id;
    private $name;
    private $points = 0;
    private $topicsCount = 0;
    private $commentsCount = 0;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * Adds points for creating a topic.
     *
     * @param int $likesCount Number of topic likes
     */
    public function addTopic($likesCount)
    {
        $this->topicsCount++;
        $this->points++;
        $this->points += ($likesCount >= 100) ? 15 : ceil($likesCount/10);
    }

    /**
     * Adds points for comment or reply.
     *
     * @param int $likesCount Number of comment likes
     * @param string $message Message of comment or reply
     */
    public function addComment($likesCount, $message)
    {
        $this->commentsCount++;
        $this->points++;
        $this->points += ($likesCount > 100) ? 11 : ceil($likesCount/10);
        $this->points += (strlen($message) > 100) ? 1 :0;
    }

    public function getPoints()
    {
        return $this->points;
    }

    public function getTopicsCount()
    {
        return $this->topicsCount;
    }

    public function getCommentsCount()
    {
        return $this->commentsCount;
    }
}