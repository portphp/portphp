<?php

namespace Port\Tests\ValueConverter;

use PHPUnit\Framework\TestCase;
use Port\ValueConverter\MappingValueConverter;

class MappingValueConverterTest extends TestCase
{
    public function testConvert()
    {
        $converter = new MappingValueConverter([
            'source' => 'destination',
            'foo' => null,
        ]);

        $this->assertSame('destination', call_user_func($converter, 'source'));
        $this->assertNull(call_user_func($converter, 'foo'));
    }

    /**
     * @expectedException \Port\Exception\UnexpectedValueException
     * @expectedExceptionMessage Cannot find mapping for value "unexpected value"
     */
    public function testExceptionIsThrownWhenValueIsNotFound()
    {
        $converter = new MappingValueConverter([]);

        call_user_func($converter, 'unexpected value');
    }
}
