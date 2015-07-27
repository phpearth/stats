<?php

namespace PHPWorldWide\Stats\Collection;

use PHPWorldWide\Stats\Collection;
use PHPWorldWide\Stats\Model\User;

class UserCollection extends Collection
{
    private $startDate;

    private $endDate;

    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;
    }

    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;
    }

    public function getTopUsers($limit = null)
    {
        $topUsers = $this->data;
        usort($topUsers, [$this, 'sortUsers']);
        $topUsers = array_reverse($topUsers, true);

        return array_slice($topUsers, 0, $limit);
    }

    /**
     * for calling with usort($myArray, [$this, 'sortUsers']);
     * @param $a
     * @param $b
     * @return mixed
     */
    private function sortUsers($a, $b){
        return $a->getPoints() - $b->getPoints();
    }

    public function addUsersFromFeed($feed)
    {
        foreach ($feed as $topic) {
            if ($topic['created_time'] >= $this->startDate && $topic['created_time'] <= $this->endDate) {
                if (isset($topic['from'])) {
                    if ($this->keyExists($topic['from']['id'])) {
                        $user = $this->get($topic['from']['id']);
                    } else {
                        $user = new User();
                        $user->setId($topic['from']['id']);
                        $user->setName($topic['from']['name']);
                        $this->add($user, $user->getId());
                    }

                    $user->addTopic();
                }
            }

            if (isset($topic['comments'])) {
                foreach ($topic['comments'] as $comment) {
                    if ($comment['created_time'] >= $this->startDate && $comment['created_time'] <= $this->endDate) {
                        if (isset($comment['from'])) {
                            if ($this->keyExists($comment['from']['id'])) {
                                $user = $this->get($comment['from']['id']);
                            } else {
                                $user = new User();
                                $user->setId($comment['from']['id']);
                                $user->setName($comment['from']['name']);
                                $this->add($user, $user->getId());
                            }

                            $user->addComment();
                        }
                    }
                }
            }
        }
    }
}