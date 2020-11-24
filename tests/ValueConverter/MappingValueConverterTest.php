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

    public function testExceptionIsThrownWhenValueIsNotFound()
    {
        $this->expectException(\Port\Exception\UnexpectedValueException::class);
        $this->expectExceptionMessage("Cannot find mapping for value \"unexpected value\"");
        $converter = new MappingValueConverter([]);

        call_user_func($converter, 'unexpected value');
    }
}
