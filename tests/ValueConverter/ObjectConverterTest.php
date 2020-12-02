<?php

namespace Port\Tests\ValueConverter;

use PHPUnit\Framework\TestCase;
use Port\Exception\UnexpectedValueException;
use Port\ValueConverter\ObjectConverter;
use RuntimeException;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
class ObjectConverterTest extends TestCase
{
    public function testGetAndSetPropertyPath()
    {
        $converter = new ObjectConverter();
        $this->assertNull($converter->getPropertyPath());

        $converter->setPropertyPath('foo.bar');
        $this->assertEquals('foo.bar', $converter->getPropertyPath());
    }

    public function testConvertWithToString()
    {
        $converter = new ObjectConverter();
        $object = new ToStringDummy();

        $this->assertEquals('foo', call_user_func($converter, $object));
    }

    public function testConvertWithPropertyPath()
    {
        $converter = new ObjectConverter('foo');
        $object = new Dummy();

        $this->assertEquals('bar', call_user_func($converter, $object));
    }

    public function testConvertAObjectWithoutToString()
    {
        $this->expectException(RuntimeException::class);
        $converter = new ObjectConverter;
        call_user_func($converter, new Dummy());
    }

    public function testConvetANonObject()
    {
        $this->expectException(UnexpectedValueException::class);
        $converter = new ObjectConverter();
        call_user_func($converter, 'foo');
    }
}

class Dummy
{
    public $foo = 'bar';
}

class ToStringDummy
{
    public function __toString()
    {
        return 'foo';
    }
}
