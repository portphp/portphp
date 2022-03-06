<?php

namespace Port\Filter;

use PHPUnit\Framework\TestCase;
use Port\ValueConverter\DateTimeValueConverter;

class DateTimeFilterTest extends TestCase
{
    public function setUp(): void
    {
        $this->items = array(
            'a' => array('updated_at' => '-3 day'),
            'b' => array('updated_at' => '-2 day'),
            'c' => array('updated_at' => '-1 day'),
            'd' => array('updated_at' => 'today'),
            'e' => array('updated_at' => 'now'),
            'f' => array('updated_at' => '+1 day'),
        );
    }

    private function applyFilter(DateTimeThresholdFilter $filter, array $items)
    {
        return array_filter($items, array($filter, '__invoke'));
    }

    public function testDefaultFilter()
    {
        $this->expectExceptionMessage("Make sure you set a threshold");
        $this->expectException(\LogicException::class);
        $this->applyFilter(
            new DateTimeThresholdFilter(new DateTimeValueConverter()),
            $this->items
        );
    }

    public function testFilter()
    {
        $resultItems = $this->applyFilter(new DateTimeThresholdFilter(
            new DateTimeValueConverter(),
            new \DateTime('today')
        ), $this->items);
        $this->assertEquals(
            array('d', 'e', 'f'),
            array_keys($resultItems)
        );
    }

    public function testSetter()
    {
        $filter = new DateTimeThresholdFilter(new DateTimeValueConverter());
        $filter->setThreshold(new \DateTime('today'));
        $resultItems = $this->applyFilter($filter, $this->items);


        $this->assertEquals(
            array('d', 'e', 'f'),
            array_keys($resultItems)
        );
    }
}
