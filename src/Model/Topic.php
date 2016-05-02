<?php

namespace PHPWorldWide\Stats\Model;

use PHPWorldWide\Stats\Collection\CommentCollection;

/**
 * Class Topic.
 */
class Topic
{
    /**
     * @var int Topic id.
     */
    private $id;

    /**
     * @var User Topic's author.
     */
    private $user;

    /**
     * @var string
     */
    private $createdTime;

    /**
     * @var int
     */
    private $likesCount = 0;

    /**
     * @var int
     */
    private $commentsCount = 0;

    /**
     * @var CommentCollection
     */
    private $comments;

    /**
     * Set topic id.
     *
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get topic id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the topic's author.
     *
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get topic author.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set created time of the topic.
     *
     * @param string $createdTime
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;
    }

    /**
     * Get created time of the topic.
     *
     * @return string
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    /**
     * Set number of topic likes.
     *
     * @param int $likesCount
     */
    public function setLikesCount($likesCount)
    {
        $this->likesCount = $likesCount;
    }

    /**
     * Get number of topic likes.
     *
     * @return int
     */
    public function getLikesCount()
    {
        return $this->likesCount;
    }

    /**
     * Set topic's number of comments and replies.
     *
     * @param int $commentsCount
     */
    public function setCommentsCount($commentsCount)
    {
        $this->commentsCount = $commentsCount;
    }

    /**
     * Get topic's number of comments and replies.
     *
     * @return int
     */
    public function getCommentsCount()
    {
        return $this->commentsCount;
    }

    /**
     * Set topic's comments collection.
     *
     * @param CommentCollection $comments
     */
    public function setComments(CommentCollection $comments)
    {
        $this->comments = $comments;
    }

    /**
     * Unique Facebook post ID in groups has format {group_id}_{topic_id}.
     * Returns only the last part of the topic ID that is needed for permalink URL.
     *
     * @return int
     */
    public function getPermalinkPostId()
    {
        return substr($this->getId(), strpos($this->getId(), '_') + 1);
    }
}
