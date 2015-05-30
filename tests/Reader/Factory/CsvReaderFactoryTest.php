<?php

namespace Port\Tests\Reader\Factory;

use Port\Reader\Factory\CsvReaderFactory;

class CsvReaderFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetReader()
    {
        $factory = new CsvReaderFactory();
        $reader = $factory->getReader(new \SplFileObject(__DIR__.'/../../Fixtures/data_column_headers.csv'));

        $this->assertInstanceOf('\Port\Reader\CsvReader', $reader);
        $this->assertCount(4, $reader);

        $factory = new CsvReaderFactory(0);
        $reader = $factory->getReader(new \SplFileObject(__DIR__.'/../../Fixtures/data_column_headers.csv'));

        $this->assertCount(3, $reader);
    }
}
