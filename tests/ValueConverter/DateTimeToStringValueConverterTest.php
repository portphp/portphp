<?php

namespace Port\Tests\ValueConverter;

use DateTime;
use PHPUnit\Framework\TestCase;
use Port\Exception\UnexpectedValueException;
use Port\ValueConverter\DateTimeToStringValueConverter;

class DateTimeToStringValueConverterTest extends TestCase
{
    public function testConvertWithoutOutputFormatReturnsString()
    {
        $value = new DateTime('2010-01-01 01:00:00');
        $converter = new DateTimeToStringValueConverter;
        $output = call_user_func($converter, $value);
        $this->assertEquals('2010-01-01 01:00:00', $output);
    }

    public function testInvalidInputFormatThrowsException()
    {
        $this->expectExceptionMessage("Input must be DateTime object");
        $this->expectException(UnexpectedValueException::class);
        $value = '14/10/2008 09:40:20';
        $converter = new DateTimeToStringValueConverter;
        call_user_func($converter, $value);
    }
}
