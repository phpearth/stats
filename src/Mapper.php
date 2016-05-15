<?php

namespace PHPWorldWide\Stats;

use PHPWorldWide\Stats\Collection\UserCollection;
use PHPWorldWide\Stats\Collection\TopicCollection;
use PHPWorldWide\Stats\Collection\CommentCollection;
use PHPWorldWide\Stats\Collection\ReplyCollection;
use PHPWorldWide\Stats\Model\Topic;
use PHPWorldWide\Stats\Model\User;
use PHPWorldWide\Stats\Model\Reply;
use PHPWorldWide\Stats\Model\Comment;

class Mapper
{
    /**
     * Mapper constructor.
     *
     * @param $config
     * @param $feed
     * @param $log
     */
    public function __construct($config, $feed, $log)
    {
        $this->config = $config;
        $this->topics = new TopicCollection();
        $this->comments = new CommentCollection();
        $this->replies = new ReplyCollection();
        $this->users = new UserCollection();
        $this->points = new Points($this->config);
        $this->log = $log;
        $this->startDate = $this->config->get('start_datetime');
        $this->endDate = $this->config->get('end_datetime');

        $this->mapFeed($feed);

        // log all contributors
        foreach ($this->users->getTopUsers() as $id => $user) {
            $log = $id."\t";
            $log .= $user->getName()."\t";
            $log .= 'Points: '.$user->getPointsCount()."\t";
            $log .= 'Topics: '.$user->getTopicsCount()."\t";
            $log .= 'Comments: '.$user->getCommentsCount();
            $this->log->logContributor($log);
        }
    }

    /**
     * Fill the collections from captured API data.
     *
     * @param array $feed All captured API data as array.
     *
     * @throws \Exception
     */
    private function mapFeed($feed)
    {
        foreach ($feed as $topic) {
            if ($topic['created_time'] >= $this->startDate && $topic['created_time'] <= $this->endDate) {
                $this->mapTopic($topic);
            }

            if (isset($topic['comments'])) {
                foreach ($topic['comments'] as $comment) {
                    if ($comment['created_time'] >= $this->startDate && $comment['created_time'] <= $this->endDate) {
                        $this->mapComment($comment);
                    }

                    if (isset($comment['comments'])) {
                        foreach ($comment['comments'] as $reply) {
                            if ($reply['created_time'] >= $this->startDate && $reply['created_time'] <= $this->endDate) {
                                $this->mapReply($reply);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Maps topic data to topic object.
     *
     * @param $topic
     *
     * @return Topic
     *
     * @throws \Exception
     */
    private function mapTopic($topic)
    {
        $newTopic = new Topic();
        $commentsCount = $topic['commentsCount'];
        if (array_key_exists('comments', $topic)) {
            foreach ($topic['comments'] as $comment) {
                if (isset($comment['comment_count'])) {
                    $commentsCount += $comment['comment_count'];
                }
            }
        }
        $newTopic->setCommentsCount($commentsCount);
        $newTopic->setId($topic['id']);
        $newTopic->setCreatedTime($topic['created_time']);
        if (array_key_exists('message', $topic)) {
            $newTopic->setMessage($topic['message']);
        }
        $newTopic->setLikesCount($topic['likesCount']);
        $newTopic->setCanComment($topic['canComment']);

        if (array_key_exists('from', $topic)) {
            $user = $this->mapUser($topic['from']);
            $user->addTopic($newTopic);
            $newTopic->setUser($user);
        }

        // Add topic to collection
        $this->topics->add($newTopic, $newTopic->getId());

        // Log topic
        $log = $newTopic->getId()."\t";
        $log .= ' Likes: '.$newTopic->getLikesCount()."\t";
        $log .= ' Comments: '.$newTopic->getCommentsCount()."\n";
        $this->log->logTopic($log);

        return $newTopic;
    }

    /**
     * Maps comment data to comment object.
     *
     * @param array $comment
     *
     * @return Comment
     *
     * @throws \Exception
     */
    private function mapComment($comment)
    {
        $newComment = new Comment();
        $newComment->setId($comment['id']);
        $newComment->setMessage($comment['message']);
        $newComment->setLikesCount($comment['like_count']);

        if (array_key_exists('from', $comment)) {
            $user = $this->mapUser($comment['from']);
            $user->addComment($newComment);

            $newComment->setUser($user);
        }

        // Add comment to collection
        $this->comments->add($newComment, $newComment->getId());

        return $newComment;
    }

    /**
     * Map reply data to reply object.
     *
     * @param $reply
     *
     * @return Reply
     *
     * @throws \Exception
     */
    private function mapReply($reply)
    {
        $newReply = new Reply();
        $newReply->setId($reply['id']);
        $newReply->setMessage($reply['message']);
        $newReply->setLikesCount($reply['like_count']);

        if (array_key_exists('from', $reply)) {
            $user = $this->mapUser($reply['from']);
            $user->addReply($newReply);

            $newReply->setUser($user);
        }

        $this->replies->add($newReply, $newReply->getId());

        return $newReply;
    }

    /**
     * @param $userData
     * @return mixed|User
     * @throws \Exception
     */
    private function mapUser($userData)
    {
        if ($this->users->keyExists($userData['id'])) {
            $user = $this->users->get($userData['id']);
        } else {
            $user = new User($this->points);
            $user->setId($userData['id']);
            $user->setName($userData['name']);
            $user->setFeedComments($this->comments);
            $user->setFeedReplies($this->replies);
            $this->users->add($user, $user->getId());
        }

        return $user;
    }

    /**
     * @return TopicCollection
     */
    public function getTopics()
    {
        return $this->topics;
    }

    /**
     * @return CommentCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return ReplyCollection
     */
    public function getReplies()
    {
        return $this->replies;
    }

    /**
     * @return UserCollection
     */
    public function getUsers()
    {
        return $this->users;
    }
}