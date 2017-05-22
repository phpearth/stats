<?php

namespace PhpEarth\Stats\Collection;

use PhpEarth\Stats\Collection;
use PhpEarth\Stats\Util\Merger;

/**
 * Class ReplyCollection.
 */
class ReplyCollection extends Collection
{
    /**
     * Merge replies created by the same user.
     *
     * @param int $userId
     *
     * @return array
     */
    public function getMergedRepliesByUserId($userId)
    {
        $merger = new Merger();
        $merger->setData($this->data);

        return $merger->getMergedItems($userId);
    }
}
