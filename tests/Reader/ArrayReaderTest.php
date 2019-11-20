<?php

namespace Port\Tests\Reader;

use PHPUnit\Framework\TestCase;
use Port\Reader\ArrayReader;

class ArrayReaderTest extends TestCase
{
    /**
     * @expectedException Assert\AssertionFailedException
     * @expectedExceptionMessage Value "invalid" is not an array and does not implement Traversable.
     */
    public function testNonGenerator()
    {
        $this->createReader('invalid');
    }

    public function testGenerator()
    {
        $reader = $this->createReader(['foo' => 'bar']);

        $this->assertInstanceOf(\Generator::class, $reader->getItems());

        $this->assertEquals(['foo' => 'bar'], iterator_to_array($reader->getItems()));
    }

    public function testCount()
    {
        $reader = $this->createReader(range(1, 100));

        $this->assertCount(100, $reader);
    }

    private function createReader($data)
    {
        return new ArrayReader($data);
    }
}
