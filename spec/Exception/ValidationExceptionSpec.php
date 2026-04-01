<?php

namespace spec\Port\Exception;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidationExceptionSpec extends ObjectBehavior
{
    private $list;

    function let()
    {
        $this->list = new ConstraintViolationList();
        $this->beConstructedWith($this->list, 1);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Port\Exception\ValidationException');
    }

    function it_is_an_exception()
    {
        $this->shouldHaveType('Exception');
        $this->shouldImplement('Port\Exception');
    }

    function it_has_a_list_of_violations()
    {
        $this->getViolations()->shouldReturn($this->list);
    }

    function it_has_a_line_number()
    {
        $this->getLineNumber()->shouldReturn(1);
    }
}
