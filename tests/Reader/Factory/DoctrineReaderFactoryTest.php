<?php

namespace Port\Tests\Reader\Factory;

use Port\Reader\Factory\DoctrineReaderFactory;

class DoctrineReaderFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetReader()
    {
        $om = $this->getMockBuilder('\Doctrine\Common\Persistence\ObjectManager')->getMock();
        $factory = new DoctrineReaderFactory($om);
        $reader = $factory->getReader('Some:Object');
        $this->assertInstanceOf('\Port\Reader\DoctrineReader', $reader);
    }
}
