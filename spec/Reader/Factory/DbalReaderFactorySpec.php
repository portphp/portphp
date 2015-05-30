<?php

namespace spec\Port\Reader\Factory;

use Doctrine\DBAL\Connection;
use PhpSpec\ObjectBehavior;

class DbalReaderFactorySpec extends ObjectBehavior
{
    function let(Connection $dbal)
    {
        $this->beConstructedWith($dbal);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Port\Reader\Factory\DbalReaderFactory');
    }

    function it_creates_a_reader()
    {
        $this->getReader('SQL', [])->shouldHaveType('Port\Reader\DbalReader');
    }
}
