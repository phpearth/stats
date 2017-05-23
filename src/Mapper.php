<?php

namespace PhpEarth\Stats;

use PhpEarth\Stats\Collection\CommentCollection;
use PhpEarth\Stats\Collection\ReplyCollection;
use PhpEarth\Stats\Collection\TopicCollection;
use PhpEarth\Stats\Collection\UserCollection;
use PhpEarth\Stats\Model\Comment;
use PhpEarth\Stats\Model\Reply;
use PhpEarth\Stats\Model\Topic;
use PhpEarth\Stats\Model\User;

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
     * Mapper constructor.
     *
     * @param Config $config
     * @param array $feed
     */
    public function __construct($config, $feed)
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

        $this->mapFeed($feed);
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
            if ($topic->getField('created_time') >= $startDate && $topic->getField('created_time') <= $endDate) {
                $this->mapTopic($topic);
            }

            foreach($topic->getField('comments', []) as $i => $comment) {
                if ($comment->getField('created_time') >= $startDate && $comment->getField('created_time') <= $endDate) {
                    $this->mapComment($comment);
                }

                foreach($comment->getField('comments', []) as $j=>$reply) {
                    if ($reply->getField('created_time') >= $startDate && $reply->getField('created_time') <= $endDate) {
                        $this->mapReply($reply);
                    }
                }
            }
        }
    }

    /**
     * Maps topic data from API feed to Topic object.
     *
     * @param array $data
     *
     * @return Topic
     *
     * @throws \Exception
     */
    private function mapTopic($data)
    {
        $topic = new Topic();

        // Count comments and replies
        $commentsCount = $data->getField('comments')->getMetaData()['summary']['total_count'];
        foreach ($data->getField('comments', []) as $comment) {
            $commentsCount += $comment->getField('comment_count', 0);
        }
        $topic->setCommentsCount($commentsCount);

        $topic->setId($data->getField('id'));
        $topic->setCreatedTime($data->getField('created_time'));
        $topic->setMessage($data->getField('message'));

        $topic->setReactionsCount($data->getField('reactions')->getMetaData()['summary']['total_count']);
        $topic->setCanComment($data->getField('comments')->getMetaData()['summary']['can_comment']);
        $topic->setType($data->getField('type'));

        $dataArray = $data->asArray();

        if ($topic->getType() == 'link' && isset($dataArray['attachments'][0]['type']) && $dataArray['attachments'][0]['type'] == 'animated_image_share') {
            $topic->setType('animated_image_share');
        }

        if (array_key_exists('from', $dataArray)) {
            $user = $this->mapUser($dataArray['from']);
            $user->addTopic($topic);
            $topic->setUser($user);
        }

        if (array_key_exists('shares', $dataArray)) {
            $topic->setSharesCount($dataArray['shares']['count']);
        }

        // Add topic to collection
        $this->topics->add($topic, $topic->getId());

        return $topic;
    }

    /**
     * Maps comment data from API feed to Comment object.
     *
     * @param array $data
     *
     * @return Comment
     *
     * @throws \Exception
     */
    private function mapComment($data)
    {
        $comment = new Comment();
        $comment->setId($data->getField('id'));
        $comment->setMessage($data->getField('message'));
        $comment->setReactionsCount($data->getField('reactions')->getMetaData()['summary']['total_count']);

        $dataArray = $data->asArray();
        if (array_key_exists('from', $dataArray)) {
            $user = $this->mapUser($dataArray['from']);
            $user->addComment($comment);

            $comment->setUser($user);
        }

        $this->comments->add($comment, $comment->getId());

        return $comment;
    }

    /**
     * Map reply data from API feed to Reply object.
     *
     * @param array $data
     *
     * @return Reply
     *
     * @throws \Exception
     */
    private function mapReply($data)
    {
        $reply = new Reply();
        $reply->setId($data->getField('id'));
        $reply->setMessage($data->getField('message'));
        $reply->setReactionsCount($data->getField('reactions')->getMetaData()['summary']['total_count']);

        $dataArray = $data->asArray();
        if (array_key_exists('from', $dataArray)) {
            $user = $this->mapUser($dataArray['from']);
            $user->addReply($reply);

            $reply->setUser($user);
        }

        $this->replies->add($reply, $reply->getId());

        return $reply;
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
