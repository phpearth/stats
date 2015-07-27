<?php

namespace PHPWorldWide\Stats\Model;

class Topic
{
    private $id;

    private $user;

    private $createdTime;

    private $likesCount;

    private $commentsCount;

    private $comments;

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

    public function setCommentsCount($commentsCount)
    {
        $this->commentsCount = $commentsCount;
    }

    public function getCommentsCount()
    {
        return $this->commentsCount;
    }

    public function setComments(CommentCollection $comments)
    {
        $this->comments = $comments;
    }

    public function getNewCommentsCount()
    {
        $count = 0;
        foreach ($this->comments as $comment) {
            if ($comment->getCreatedTime() >= $startDate && $comment->getCreatedTime() <= $endDate) {
                $count ++;
            }
        }

        return $count;
    }
}