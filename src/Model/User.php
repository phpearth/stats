<?php

namespace PHPWorldWide\Stats\Model;


class User
{
    private $id;
    private $name;
    private $points = 0;

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
        $this->points++;
        $this->points += ($likesCount >= 100) ? 15 : ceil($likesCount/10);
    }

    /**
     * Adds points for comment or reply.
     *
     * @param int $likesCount Number of comment likes
     */
    public function addComment($likesCount)
    {
        $this->points++;
        $this->points += ($likesCount >= 100) ? 15 : ceil($likesCount/10);
    }

    public function getPoints()
    {
        return $this->points;
    }
}