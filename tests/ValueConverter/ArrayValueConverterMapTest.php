<?php

namespace Port\Tests\ValueConverter;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Port\ValueConverter\ArrayValueConverterMap;

/**
 * @author Christoph Rosse <christoph@rosse.at>
 */
class ArrayValueConverterMapTest extends TestCase
{
    public function testConvertWithNoArrayArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $converter = new ArrayValueConverterMap(array('foo' => function($input) {return $input;}));
        call_user_func($converter, 'foo');
    }

    public function testConvertWithMultipleFields()
    {
        $data = array(
            array(
                'foo' => 'test',
                'bar' => 'test'
            ),
            array(
                'foo' => 'test2',
                'bar' => 'test2'
            ),
        );

        $addBarConverter = function($input) { return 'bar'.$input; };
        $addBazConverter = function($input) { return 'baz'.$input; };

        $converter = new ArrayValueConverterMap(
            array(
                'foo' => array($addBarConverter),
                'bar' => array($addBazConverter, $addBarConverter),
            )
        );

        $data = call_user_func($converter, $data);

        $this->assertEquals('bartest', $data[0]['foo']);
        $this->assertEquals('barbaztest', $data[0]['bar']);

        $this->assertEquals('bartest2', $data[1]['foo']);
        $this->assertEquals('barbaztest2', $data[1]['bar']);
    }
}
