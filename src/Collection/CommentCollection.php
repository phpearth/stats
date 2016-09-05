<?php

namespace PHPWorldWide\Stats\Collection;

use PHPWorldWide\Stats\Collection;
use PHPWorldWide\Stats\Util\Merger;

/**
 * Class CommentCollection.
 */
class CommentCollection extends Collection
{
    /**
     * Merge comments created by the same user.
     *
     * @param int $userId
     *
     * @return array
     */
    public function getMergedCommentsByUserId($userId)
    {
        $merger = new Merger();
        $merger->setData($this->data);

        return $merger->getMergedItems($userId);
    }
}
