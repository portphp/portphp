<?php

namespace Port\Tests\Filter;

use PHPUnit\Framework\TestCase;
use Port\Filter\OffsetFilter;

/**
 * @author Ville Mattila <ville@eventio.fi>
 */
class OffsetFilterTest extends TestCase
{

    private function applyFilter(OffsetFilter $filter, array $items) {
        $result = array();
        foreach ($items as $item) {
            if (true === call_user_func($filter, array($item))) {
                $result[] = $item;
            }
        }

        return $result;
    }

    public function testDefaultFilter()
    {
        $items = array('first','second','third','fourth');
        $resultItems = $this->applyFilter(new OffsetFilter(), $items);
        $this->assertEquals($resultItems, $items);
    }

    public function testStartOffset()
    {
        $items = array('first','second','third','fourth');
        $resultItems = $this->applyFilter(new OffsetFilter(1), $items);
        $this->assertEquals(array('second','third','fourth'), $resultItems);
    }

    public function testMaxCount()
    {
        $items = array('first','second','third','fourth');
        $resultItems = $this->applyFilter(new OffsetFilter(0, 2), $items);
        $this->assertEquals(array('first','second'), $resultItems);
    }

    public function testOffsetWithMaxCount()
    {
        $items = array('first','second','third','fourth');
        $resultItems = $this->applyFilter(new OffsetFilter(1, 1), $items);
        $this->assertEquals(array('second'), $resultItems);
    }
}
