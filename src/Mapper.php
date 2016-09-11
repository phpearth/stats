<?php

namespace PHPWorldWide\Stats;

use PHPWorldWide\Stats\Collection\CommentCollection;
use PHPWorldWide\Stats\Collection\ReplyCollection;
use PHPWorldWide\Stats\Collection\TopicCollection;
use PHPWorldWide\Stats\Collection\UserCollection;
use PHPWorldWide\Stats\Model\Comment;
use PHPWorldWide\Stats\Model\Reply;
use PHPWorldWide\Stats\Model\Topic;
use PHPWorldWide\Stats\Model\User;

/**
 * Class Mapper
 */
class Mapper
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var TopicCollection
     */
    private $topics;

    /**
     * @var CommentCollection
     */
    private $comments;

    /**
     * @var ReplyCollection
     */
    private $replies;

    /**
     * @var UserCollection
     */
    private $users;

    /**
     * @var Points
     */
    private $points;

    /**
     * @var Log
     */
    private $log;

    /**
     * Mapper constructor.
     *
     * @param Config $config
     * @param array $feed
     * @param Log $log
     */
    public function __construct($config, $feed, $log)
    {
        $this->config = $config;
        $this->topics = new TopicCollection();
        $this->comments = new CommentCollection();
        $this->replies = new ReplyCollection();
        $this->users = new UserCollection();
        $this->points = new Points();
        $this->points->setPoints($this->config->get('points'));
        $this->points->setAdmins($this->config->getParameter('admins'));
        $this->points->setOffensiveWords($this->config->get('offensive_words'));
        $this->log = $log;

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
        $startDate = $this->config->getParameter('start_datetime');
        $endDate = $this->config->getParameter('end_datetime');

        foreach ($feed as $topic) {
            if ($topic['created_time'] >= $startDate && $topic['created_time'] <= $endDate) {
                $this->mapTopic($topic);
            }

            if (isset($topic['comments'])) {
                foreach ($topic['comments'] as $comment) {
                    if ($comment['created_time'] >= $startDate && $comment['created_time'] <= $endDate) {
                        $this->mapComment($comment);
                    }

                    if (isset($comment['comments'])) {
                        foreach ($comment['comments'] as $reply) {
                            if ($reply['created_time'] >= $startDate && $reply['created_time'] <= $endDate) {
                                $this->mapReply($reply);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Maps topic data from API feed to Topic object.
     *
     * @param array $topic
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
        $newTopic->setReactionsCount($topic['reactionsCount']);
        $newTopic->setCanComment($topic['canComment']);
        $newTopic->setType($topic['type']);
        if ($newTopic->getType() == 'link' && isset($topic['attachments'][0]['type']) && $topic['attachments'][0]['type'] == 'animated_image_share') {
            $newTopic->setType('animated_image_share');
        }

        if (array_key_exists('from', $topic)) {
            $user = $this->mapUser($topic['from']);
            $user->addTopic($newTopic);
            $newTopic->setUser($user);
        }

        // Add topic to collection
        $this->topics->add($newTopic, $newTopic->getId());

        // Log topic
        $log = $newTopic->getId()."\t";
        $log .= ' Reactions: '.$newTopic->getReactionsCount()."\t";
        $log .= ' Comments: '.$newTopic->getCommentsCount()."\n";
        $this->log->logTopic($log);

        return $newTopic;
    }

    /**
     * Maps comment data from API feed to Comment object.
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

        $this->comments->add($newComment, $newComment->getId());

        return $newComment;
    }

    /**
     * Map reply data from API feed to Reply object.
     *
     * @param array $reply
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
     * Map user's data from API feed to User object.
     *
     * @param array $userData
     *
     * @return User
     *
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
     * Get mapped topics collection.
     *
     * @return TopicCollection
     */
    public function getTopics()
    {
        return $this->topics;
    }

    /**
     * Get mapped comments collection.
     *
     * @return CommentCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Get mapped replies collection.
     *
     * @return ReplyCollection
     */
    public function getReplies()
    {
        return $this->replies;
    }

    /**
     * Get mapped users collection.
     *
     * @return UserCollection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
