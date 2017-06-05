<?php

namespace PhpEarth\Stats\Model;

/**
 * Class Reply
 * @package PhpEarth\Stats\Model
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
    private $reactionsCount = 0;

    /**
     * @var string|null
     */
    private $message = null;

    /**
     * @var int Topic id in which reply was created
     */
    private $topicId;

    /**
     * @var int Comment id in which reply was created
     */
    private $commentId;

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

    /**
     * Set number of reply's reactions.
     *
     * @param int $reactionsCount
     */
    public function setReactionsCount($reactionsCount)
    {
        $this->reactionsCount = $reactionsCount;
    }

    /**
     * Get number of reply's reactions.
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

    /**
     * Set comment id.
     *
     * @param int $commentId
     */
    public function setCommentId($commentId)
    {
        $this->commentId = $commentId;
    }

    /**
     * Get comment id.
     *
     * @return int
     */
    public function getCommentId()
    {
        return $this->commentId;
    }
}
