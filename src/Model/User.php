<?php

namespace PHPWorldWide\Stats\Model;

use PHPWorldWide\Stats\Points;
use PHPWorldWide\Stats\Collection;
use PHPWorldWide\Stats\Collection\CommentCollection;
use PHPWorldWide\Stats\Collection\ReplyCollection;

/**
 * Class User.
 */
class User
{
    /**
     * @var int User's id
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Points
     */
    private $points;

    /**
     * @var int
     */
    private $pointsCount = 0;

    /**
     * @var Collection
     */
    private $topics;

    /**
     * @var Collection
     */
    private $comments;

    /**
     * @var Collection
     */
    private $replies;

    /**
     * @var CommentCollection
     */
    private $feedComments;

    /**
     * @var ReplyCollection
     */
    private $feedReplies;

    /**
     * User constructor.
     *
     * @param Points $points
     */
    public function __construct(Points $points)
    {
        $this->points = $points;
        $this->topics = new Collection();
        $this->comments = new CommentCollection();
        $this->replies = new Collection();
    }

    /**
     * Set entire comments collection.
     *
     * @param CommentCollection $feedComments
     */
    public function setFeedComments(CommentCollection $feedComments)
    {
        $this->feedComments = $feedComments;
    }

    /**
     * Set entire replies collection.
     *
     * @param ReplyCollection $feedReplies
     */
    public function setFeedReplies(ReplyCollection $feedReplies)
    {
        $this->feedReplies = $feedReplies;
    }

    /**
     * Set user's id.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get user's id.
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user's name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get user's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Increase number of topics and add points for creating a topic.
     *
     * @param Topic $topic User's topic
     */
    public function addTopic(Topic $topic)
    {
        $this->topics->add($topic, $topic->getId());
    }

    /**
     * Increase number of comments and add points.
     *
     * @param Comment $comment
     */
    public function addComment(Comment $comment)
    {
        $this->comments->add($comment, $comment->getId());
    }

    /**
     * Increase number of comments and add points.
     *
     * @param Reply $reply
     */
    public function addReply(Reply $reply)
    {
        $this->replies->add($reply, $reply->getId());
    }

    /**
     * Get number of user's points.
     *
     * @return int
     */
    public function getPointsCount()
    {
        if ($this->pointsCount === 0) {
            $pointsCount = 0;

            foreach ($this->topics as $topic) {
                $pointsCount += $this->points->addPointsForTopic($topic);
            }

            $mergedComments = $this->feedComments->getMergedCommentsByUserId($this->getId());

            foreach ($mergedComments as $comment) {
                $pointsCount += $this->points->addPointsForComment($comment);
            }

            $mergedReplies = $this->feedReplies->getMergedRepliesByUserId($this->getId());
            foreach ($mergedReplies as $reply) {
                $pointsCount += $this->points->addPointsForReply($reply);
            }

            $this->pointsCount = $pointsCount;
        }

        return $this->pointsCount;
    }

    /**
     * Get number of user's topics.
     *
     * @return int
     */
    public function getTopicsCount()
    {
        return $this->topics->count();
    }

    /**
     * Get number of user's comments and replies.
     *
     * @return int
     */
    public function getCommentsCount()
    {
        return $this->comments->count() + $this->replies->count();
    }
}
