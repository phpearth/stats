<?php

namespace PHPWorldWide\Stats;

class Collection implements \Countable, \Iterator
{
    protected $data = [];

    public function add($obj, $key = null)
    {
        if ($key == null) {
            $this->data[] = $obj;
        } else {
            if (isset($this->data[$key])) {
                throw new Exception("Key $key already in use.");
            } else {
                $this->data[$key] = $obj;
            }
        }
    }

    public function delete($key)
    {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        } else {
            throw new Exception("Invalid key $key.");
        }
    }

    public function get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            throw new Exception("Invalid key $key.");
        }
    }

    public function keyExists($key) {
        return isset($this->data[$key]);
    }

    public function count()
    {
        return count($this->data);
    }

    public function rewind()
    {
        reset($this->data);
    }

    public function current()
    {
        $data = current($this->data);
        return $data;
    }

    public function key()
    {
        $data = key($this->data);
        return $data;
    }

    public function next()
    {
        $data = next($this->data);
        return $data;
    }

    public function valid()
    {
        $key = key($this->data);
        $data = ($key !== NULL && $key !== FALSE);
        return $data;
    }
}