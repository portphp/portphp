<?php

namespace Port\Tests\Step;

use Port\Step\ValueConverterStep;

class ValueConverterStepTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->filter = new ValueConverterStep();
    }

    public function testProcess()
    {
        $this->filter->add('[foo]', function($v) { return 'barfoo'; });

        $data = ['foo' => 'foobar'];
        $this->filter->process($data);

        $this->assertEquals(['foo' => 'barfoo'], $data);
    }
}
