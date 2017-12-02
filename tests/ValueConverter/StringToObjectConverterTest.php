<?php

namespace Port\Tests\ValueConverter;

use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use Port\ValueConverter\StringToObjectConverter;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class StringToObjectConverterTest extends TestCase
{
    public function testConvert()
    {
        $repository = $this->getMockBuilder(ObjectRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['find', 'findAll', 'findBy', 'findOneBy', 'getClassName', 'findOneByName'])
            ->getMock();

        $converter = new StringToObjectConverter($repository, 'name');

        $class = new \stdClass();

        $repository->expects($this->once())
            ->method('findOneByName')
            ->with('bar')
            ->will($this->returnValue($class));

        $this->assertEquals($class, call_user_func($converter, 'bar'));
    }
}
