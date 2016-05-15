<?php

namespace PHPWorldWide\Stats\Collection;

use PHPWorldWide\Stats\Collection;

/**
 * Class UserCollection.
 */
class UserCollection extends Collection
{
    /**
     * @var array
     */
    private $topUsers = [];

    /**
     * Returns top active members of the given period.
     *
     * @param string|null $limit
     * @param array $ignoredUsers
     *
     * @return mixed
     */
    public function getTopUsers($limit = null, array $ignoredUsers = [])
    {
        if (sizeof($this->topUsers) == 0) {
            $users = $this->data;
            usort($users, [$this, 'sortUsers']);
            $users = array_reverse($users, true);

            $this->topUsers = $users;
        }

        $topUsers = [];
        foreach ($this->topUsers as $user) {
            if (!in_array($user->getName(), $ignoredUsers)) {
                $topUsers[] = $user;
            }
        }

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
        return $a->getPointsCount() - $b->getPointsCount();
    }
}
