<?php

namespace Port\Tests\Reader;

use PHPUnit\Framework\TestCase;
use Port\Reader\AppendReader;
use Port\Reader\ArrayReader;

class AppendReaderTest extends TestCase
{
    protected function setup()
    {
        $this->reader = new AppendReader([]);
    }

    public function testAppend()
    {
        $this->reader->addReader(new ArrayReader(range(1, 10)));
        $this->reader->addReader(new ArrayReader(range(1, 10)));

        $this->assertCount(20, $this->reader);
    }

    public function testGetItems()
    {
        $this->reader->addReader(new ArrayReader(range(1, 10)));
        $this->reader->addReader(new ArrayReader(range(11, 20)));

        $i = 1;

        foreach ($this->reader->getItems() as $value) {
            $this->assertEquals($i++, $value);
        }
    }
}
