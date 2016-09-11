<?php

namespace PHPWorldWide\Stats\Util;

/**
 * Merges sequential comments or replies.
 */
class Merger
{
    /**
     * Set comments or replies to merge.
     *
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Merges sequential comments or replies and return merged array for a given user.
     *
     * @param int $userId
     * @return array
     */
    public function getMergedItems($userId)
    {
        $items = array_values($this->data);
        $mergedItems = [];
        $i = 0;
        foreach ($items as $item) {
            if ($item->getUser()->getId() == $userId) {
                if (isset($items[$i-1]) && $items[$i-1]->getUser()->getId() == $userId) {
                    $item->setMessage($items[$i-1]->getMessage().$item->getMessage());
                    $item->setLikesCount(max($item->getLikesCount(), $items[$i-1]->getLikesCount()));
                    unset($mergedItems[$i-1]);
                }
                $mergedItems[$i] = $item;
            }
            $i++;
        }

        $items = [];
        foreach ($mergedItems as $item) {
            $items[$item->getId()] = $item;
        }

        return $items;
    }
}
