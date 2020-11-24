<?php

namespace spec\Port;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResultSpec extends ObjectBehavior
{
    function let(\SplObjectStorage $exceptions)
    {
        $startTime = new \DateTime('2018-02-10T00:00:00+00:00');
        $endTime = new \DateTime('2018-02-11T00:00:00+00:00');
        $exceptions->count()->willReturn(4);
        $this->beConstructedWith('name', $startTime, $endTime, 10, $exceptions);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Port\Result');
    }

    function it_has_a_name()
    {
        $this->getName()->shouldReturn('name');
    }

    function it_has_a_start_time()
    {
        $this->getStartTime()->shouldBeLike(new \DateTime('2018-02-10T00:00:00+00:00'));
    }

    function it_has_a_end_time()
    {
        $this->getEndTime()->shouldBeLike(new \DateTime('2018-02-11T00:00:00+00:00'));
    }

    function it_has_an_elapsed_time()
    {
        // to be checked!? - the following throws an error:
        // Cannot compare DateInterval objects in vendor\phpspec\phpspec\src\PhpSpec\Matcher\ComparisonMatcher.php line 44
        // $this->getElapsed()->shouldBeLike(new \DateInterval('P1D'));
        $this->getElapsed()->shouldBeAnInstanceOf(\DateInterval::class);
    }

    function it_has_an_error_count()
    {
        $this->getErrorCount()->shouldReturn(4);
    }

    function it_has_a_success_count()
    {
        $this->getSuccessCount()->shouldReturn(6);
    }

    function it_has_a_total_processed_count()
    {
        $this->getTotalProcessedCount()->shouldReturn(10);
    }

    function it_checks_if_it_has_errors()
    {
        $this->hasErrors()->shouldReturn(true);
    }

    function it_has_exceptions(\SplObjectStorage $exceptions)
    {
        $this->getExceptions()->shouldReturn($exceptions);
    }
}
