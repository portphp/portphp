<?php

namespace spec\Port\Reader;

use PhpSpec\ObjectBehavior;
use Port\Reader;
use Prophecy\Argument;

class AppendReaderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Port\Reader\AppendReader');
    }

    function it_is_a_reader()
    {
        $this->shouldImplement('Port\Reader');
    }

    function it_accepts_an_array_of_readers(Reader $reader, Reader $reader2)
    {
        $readers = [$reader, $reader2];

        $this->beConstructedWith($readers);
    }
}
