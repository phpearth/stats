<?php

namespace PHPWorldWide\Stats\Model;

class Comment
{
    private $id;

    private $user;

    private $createdTime;

    private $likesCount;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;
    }

    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    public function setLikesCount($likesCount)
    {
        $this->likesCount = $likesCount;
    }

    public function getLikesCount()
    {
        return $this->likesCount;
    }
}
