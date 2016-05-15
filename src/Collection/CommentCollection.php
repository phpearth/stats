<?php

namespace PHPWorldWide\Stats\Collection;

use PHPWorldWide\Stats\Collection;

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
        $comments = array_values($this->data);
        $mergedComments = [];
        $i = 0;
        foreach ($comments as $comment) {
            if ($comment->getUser()->getId() == $userId) {
                if (isset($comments[$i-1]) && $comments[$i-1]->getUser()->getId() == $userId) {
                    $comment->setMessage($comments[$i-1]->getMessage().$comment->getMessage());
                    $comment->setLikesCount(max($comment->getLikesCount(), $comments[$i-1]->getLikesCount()));
                    unset($mergedComments[$i-1]);
                }
                $mergedComments[$i] = $comment;
            }
            $i++;
        }

        $comments = [];
        foreach ($mergedComments as $comment) {
            $comments[$comment->getId()] = $comment;
        }

        return $comments;
    }
}
