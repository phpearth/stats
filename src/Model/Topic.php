<?php

namespace PhpEarth\Stats\Model;

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
    private $reactionsCount = 0;

    /**
     * @var int
     */
    private $commentsCount = 0;

    /**
     * @var string
     */
    private $message = '';

    /**
     * @var bool
     */
    private $canComment = true;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $sharesCount = 0;

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
     * Set number of topic reactions.
     *
     * @param int $reactionsCount
     */
    public function setReactionsCount($reactionsCount)
    {
        $this->reactionsCount = $reactionsCount;
    }

    /**
     * Get number of topic reactions.
     *
     * @return int
     */
    public function getReactionsCount()
    {
        return $this->reactionsCount;
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
     * Set topic's message.
     *
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Get topic's message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the boolean value whether the commenting is turned off or not.
     *
     * @param bool $canComment
     */
    public function setCanComment($canComment)
    {
        $this->canComment = $canComment;
    }

    /**
     * Get post type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the topic type (photo, status, animated_image_share...)
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get boolean value whether topic has comments turned off or not.
     *
     * @return bool
     */
    public function getCanComment()
    {
        return $this->canComment;
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

    /**
     * Set topic's number of shares.
     *
     * @param int $sharesCount
     */
    public function setSharesCount($sharesCount)
    {
        $this->sharesCount = $sharesCount;
    }

    /**
     * Get topic's number of shares.
     *
     * @return int
     */
    public function getSharesCount()
    {
        return $this->sharesCount;
    }
}
