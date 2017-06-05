<?php

namespace PhpEarth\Stats\Model;

/**
 * Class Comment
 * @package PhpEarth\Stats\Model
 */
class Comment
{
    /**
     * @var int Comment's id
     */
    private $id;

    /**
     * @var User Comment's author.
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
     * @var string|null
     */
    private $message = null;

    /**
     * @var int Topic id in which comment was created
     */
    private $topicId;

    /**
     * Set comment's id.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get comment id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set comment's author.
     *
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get comment's author.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set comment's created time.
     *
     * @param string $createdTime
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;
    }

    /**
     * Get comment's created time.
     *
     * @return string
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    /**
     * Set comment message
     *
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Get comment message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set number of comment's reactions.
     *
     * @param int $reactionsCount
     */
    public function setReactionsCount($reactionsCount)
    {
        $this->reactionsCount = $reactionsCount;
    }

    /**
     * Get number of comment's reactions.
     *
     * @return int
     */
    public function getReactionsCount()
    {
        return $this->reactionsCount;
    }

    /**
     * Set topic id.
     *
     * @param int $topicId
     */
    public function setTopicId($topicId)
    {
        $this->topicId = $topicId;
    }

    /**
     * Get topic id.
     *
     * @return int
     */
    public function getTopicId()
    {
        return $this->topicId;
    }
}
