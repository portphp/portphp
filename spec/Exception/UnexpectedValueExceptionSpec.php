<?php

namespace spec\Port\Exception;

use PhpSpec\ObjectBehavior;

class UnexpectedValueExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Port\Exception\UnexpectedValueException');
    }

    function it_is_an_exception()
    {
        $this->shouldImplement('Port\Exception');
    }
}
