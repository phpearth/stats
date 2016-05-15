<?php

namespace PHPWorldWide\Stats\Tests;

use PHPWorldWide\Stats\Collection;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $collection = new Collection();
        $object = new \stdClass();
        $object->foo = 'bar';
        $collection->add($object);
        
        $this->assertEquals(1, count($collection));
    }

    public function testDelete()
    {
        $collection = new Collection();
        $object = new \stdClass();
        $object->foo = 'bar';
        $collection->add($object, 'baz');

        $this->assertEquals(1, count($collection));

        $collection->delete('baz');

        $this->assertEquals(0, count($collection));
    }

    public function testGet()
    {
        $collection = new Collection();
        $object = new \stdClass();
        $object->foo = 'bar';
        $collection->add($object, 'baz');

        $this->assertEquals($object, $collection->get('baz'));
    }

    public function testKeyExists()
    {
        $collection = new Collection();
        $object = new \stdClass();
        $object->foo = 'bar';
        $collection->add($object, 'baz');

        $this->assertTrue($collection->keyExists('baz'));
        $this->assertFalse($collection->keyExists('qux'));
    }

    public function testCount()
    {
        $collection = new Collection();
        for ($i = 0; $i<4; $i++) {
            $object = new \stdClass();
            $collection->add($object);
        }

        $this->assertEquals(4, $collection->count());
    }

    public function testCurrent()
    {
        $collection = new Collection();
        $objects = [];
        for ($i = 0; $i<4; $i++) {
            $object = new \stdClass();
            $objects[] = $object;
            $collection->add($object);
        }

        $this->assertEquals(end($objects), $collection->current());
    }

    public function testRewind()
    {
        $collection = new Collection();
        $objects = [];
        for ($i = 0; $i<4; $i++) {
            $object = new \stdClass();
            $objects[] = $object;
            $collection->add($object);
        }

        $collection->rewind();

        $this->assertEquals($objects[0], $collection->current());
    }

    public function testKey()
    {
        $collection = new Collection();
        $objects = [];
        for ($i = 0; $i<4; $i++) {
            $object = new \stdClass();
            $objects[] = $object;
            $collection->add($object);
        }

        $this->assertEquals(0, $collection->key());
    }

    public function testNext()
    {
        $collection = new Collection();
        $objects = [];
        for ($i = 0; $i<4; $i++) {
            $object = new \stdClass();
            $objects[] = $object;
            $collection->add($object);
        }

        $this->assertEquals($objects[0], $collection->next());
        $this->assertEquals($objects[1], $collection->next());
    }
}