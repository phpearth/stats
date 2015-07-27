<?php

namespace PHPWorldWide\Stats\Model;


class User
{
    private $id;
    private $name;
    private $topicsCount = 0;
    private $commentsCount = 0;
    private $likesCount = 0;

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

    public function addTopic()
    {
        $this->topicsCount ++;
    }

    public function getTopicsCount()
    {
        return $this->topicsCount;
    }

    public function addComment()
    {
        $this->commentsCount ++;
    }

    public function getCommentsCount()
    {
        return $this->commentsCount;
    }

    public function addLike()
    {
        $this->likesCount ++;
    }

    public function getLikesCount()
    {
        return $this->likesCount;
    }

    public function getPoints()
    {
        return $this->topicsCount + $this->commentsCount;
    }
}