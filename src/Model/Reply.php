<?php

namespace PHPWorldWide\Stats\Model;

/**
 * Class Reply
 * @package PHPWorldWide\Stats\Model
 */
class Reply
{
    /**
     * @var int Reply's id.
     */
    private $id;

    /**
     * @var User Reply's author.
     */
    private $user;

    /**
     * @var Comment Reply's parent comment.
     */
    private $comment;

    /**
     * @var string Reply's created time.
     */
    private $createdTime;

    /**
     * @var int
     */
    private $likesCount = 0;

    /**
     * @var string|null
     */
    private $message = null;

    /**
     * Set reply's id.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get reply's id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set reply's author.
     *
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get reply's author.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set reply's parent comment object.
     *
     * @param Comment $comment
     */
    public function setComment(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get reply's parent comment object.
     *
     * @return Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set reply's created time.
     *
     * @param string $createdTime
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;
    }

    /**
     * Get reply's created time.
     *
     * @return string
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    /**
     * Set number of reply's likes.
     *
     * @param int $likesCount
     */
    public function setLikesCount($likesCount)
    {
        $this->likesCount = $likesCount;
    }

    /**
     * Get number of reply's likes
     * @return int
     */
    public function getLikesCount()
    {
        return $this->likesCount;
    }

    /**
     * Set reply message
     *
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Get reply message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
