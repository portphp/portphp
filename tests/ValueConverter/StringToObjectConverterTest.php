<?php

namespace Port\Tests\ValueConverter;

use Port\ValueConverter\StringToObjectConverter;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class StringToObjectConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testConvert()
    {
        $repository = $this->getMockBuilder(
            'Doctrine\\Common\\Persistence\\ObjectRepository',
            ['find', 'findAll', 'findBy', 'findOneBy', 'getClassName', 'findOneByName']
        )
            ->setMethods(['findOneByName'])
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
