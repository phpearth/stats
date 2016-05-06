<?php

namespace PHPWorldWide\Stats\Collection;

use PHPWorldWide\Stats\Collection;
use PHPWorldWide\Stats\Model\User;
use PHPWorldWide\Stats\Model\Topic;
use PHPWorldWide\Stats\Model\Comment;
use PHPWorldWide\Stats\Model\Reply;
use PHPWorldWide\Stats\Points;
use PHPWorldWide\Stats\Config;

/**
 * Class UserCollection.
 */
class UserCollection extends Collection
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
    }
    /**
     * Returns top active members of the given period.
     *
     * @param string|null $limit
     *
     * @return mixed
     */
    public function getTopUsers($limit = null)
    {
        $topUsers = $this->data;
        usort($topUsers, [$this, 'sortUsers']);
        $topUsers = array_reverse($topUsers, true);

        return array_slice($topUsers, 0, $limit);
    }

    /**
     * For calling with usort($myArray, [$this, 'sortUsers']);.
     *
     * @param $a
     * @param $b
     *
     * @return mixed
     */
    private function sortUsers($a, $b)
    {
        return $a->getPoints() - $b->getPoints();
    }

    /**
     * Fill the users collection from captured API data.
     *
     * @param array $feed All captured API data as array.
     *
     * @throws \Exception
     */
    public function addUsersFromFeed($feed)
    {
        foreach ($feed as $topic) {
            if ($topic['created_time'] >= $this->startDate && $topic['created_time'] <= $this->endDate) {
                if (isset($topic['from'])) {
                    if ($this->keyExists($topic['from']['id'])) {
                        $user = $this->get($topic['from']['id']);
                    } else {
                        $points = new Points($this->config);
                        $user = new User($points);
                        $user->setId($topic['from']['id']);
                        $user->setName($topic['from']['name']);
                        $this->add($user, $user->getId());
                    }

                    $topicModel = new Topic();
                    $topicModel->setId($topic['id']);
                    if (isset($topic['message'])) {
                        $topicModel->setMessage($topic['message']);
                    }
                    $topicModel->setLikesCount($topic['likesCount']);

                    $user->addTopic($topicModel);
                }
            }

            if (isset($topic['comments'])) {
                foreach ($topic['comments'] as $comment) {
                    if ($comment['created_time'] >= $this->startDate && $comment['created_time'] <= $this->endDate && isset($comment['from'])) {
                        if ($this->keyExists($comment['from']['id'])) {
                            $user = $this->get($comment['from']['id']);
                        } else {
                            $points = new Points($this->config);
                            $user = new User($points);
                            $user->setId($comment['from']['id']);
                            $user->setName($comment['from']['name']);
                            $this->add($user, $user->getId());
                        }

                        $commentModel = new Comment();
                        $commentModel->setId($comment['id']);
                        $commentModel->setMessage($comment['message']);
                        $commentModel->setLikesCount($comment['like_count']);

                        $user->addComment($commentModel);
                    }

                    if (isset($comment['comments'])) {
                        foreach ($comment['comments'] as $reply) {
                            if ($reply['created_time'] >= $this->startDate && $reply['created_time'] <= $this->endDate && isset($reply['from'])) {
                                if ($this->keyExists($reply['from']['id'])) {
                                    $user = $this->get($reply['from']['id']);
                                } else {
                                    $points = new Points($this->config);
                                    $user = new User($points);
                                    $user->setId($reply['from']['id']);
                                    $user->setName($reply['from']['name']);
                                    $this->add($user, $user->getId());
                                }

                                $replyModel = new Reply();
                                $replyModel->setId($reply['id']);
                                $replyModel->setMessage($reply['message']);
                                $replyModel->setLikesCount($reply['like_count']);
                                $user->addReply($replyModel);
                            }
                        }
                    }
                }
            }
        }
    }
}
