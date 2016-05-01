<?php

namespace PHPWorldWide\Stats;

class Collection implements \Countable, \Iterator
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * Adds object to collection with key.
     *
     * @param $obj
     * @param null $key
     *
     * @throws \Exception
     */
    public function add($obj, $key = null)
    {
        if ($key == null) {
            $this->data[] = $obj;
        } else {
            if (isset($this->data[$key])) {
                throw new \Exception("Key $key already in use.");
            } else {
                $this->data[$key] = $obj;
            }
        }
    }

    /**
     * Deletes object from collection by key.
     *
     * @param $key
     *
     * @throws \Exception
     */
    public function delete($key)
    {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        } else {
            throw new \Exception("Invalid key $key.");
        }
    }

    /**
     * Returns object from collection.
     *
     * @param $key
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            throw new \Exception("Invalid key $key.");
        }
    }

    /**
     * Checks if object with given key exists in collection.
     *
     * @param $key
     *
     * @return bool
     */
    public function keyExists($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * Returns number of objects in collection.
     *
     * @return mixed
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * Rewinds the internal pointer of an array to its first object of collection.
     */
    public function rewind()
    {
        reset($this->data);
    }

    /**
     * Returns the current object in a collection.
     *
     * @return mixed
     */
    public function current()
    {
        $data = current($this->data);

        return $data;
    }

    /**
     * Fetch a key from an collection.
     *
     * @return mixed
     */
    public function key()
    {
        $data = key($this->data);

        return $data;
    }

    /**
     * Advance the internal array pointer of a collection.
     *
     * @return mixed
     */
    public function next()
    {
        $data = next($this->data);

        return $data;
    }

    /**
     * Checks if key is set in the collection.
     *
     * @return bool
     */
    public function valid()
    {
        $key = key($this->data);
        $data = ($key !== null && $key !== false);

        return $data;
    }
}
